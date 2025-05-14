<?php

namespace App\Livewire;

use App\Models\Teaser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

/**
 * TeaserUpdate Component
 *
 * This component handles updating a single teaser.
 */
class TeaserUpdate extends Component
{
    use WithFileUploads;

    /** @var Teaser The teaser being updated */
    public Teaser $teaser;

    /** @var string The title of the teaser */
    public string $title = '';

    /** @var string The description of the teaser */
    public string $description = '';

    /** @var string The slug of the teaser */
    public string $slug = '';

    /** @var mixed The uploaded image */
    public $image = null;

    /** @var string|null The path to the image */
    public ?string $image_name = null;

    /** @var bool Whether the component is loading/processing */
    public bool $isLoading = false;

    /** @var string Success message */
    public string $successMessage = '';

    /**
     * Initialize the component with a teaser.
     *
     * @param Teaser $teaser
     * @return void
     */
    public function mount(Teaser $teaser): void
    {
        if (!Auth::check()) {
            session()->flash('error', 'Sie müssen angemeldet sein, um auf diese Seite zuzugreifen.');
            $this->redirect(route('login'));
            return;
        }

        if ($teaser->user_id !== Auth::id()) {
            session()->flash('error', 'Sie sind nicht berechtigt, diesen Teaser zu bearbeiten.');
            $this->redirect(route('teasers.index'));
            return;
        }

        $this->teaser = $teaser;
        $this->loadTeaserData();
    }

    /**
     * Load teaser data into component properties
     *
     * @return void
     */
    private function loadTeaserData(): void
    {
        $this->title = $this->teaser->title;
        $this->description = $this->teaser->description;
        $this->slug = $this->teaser->slug;
        $this->image_name = $this->teaser->image_name;
    }

    /**
     * Get validation rules for the form
     *
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:1000',
            'slug' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
        ];
    }

    /**
     * Get custom validation messages
     *
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'title.required' => 'Der Titel ist erforderlich.',
            'title.min' => 'Der Titel muss mindestens 3 Zeichen lang sein.',
            'title.max' => 'Der Titel darf maximal 255 Zeichen lang sein.',

            'description.required' => 'Der Text ist erforderlich.',
            'description.min' => 'Der Text muss mindestens 10 Zeichen lang sein.',
            'description.max' => 'Der Text darf maximal 1000 Zeichen lang sein.',

            'slug.required' => 'Der Slug ist erforderlich.',

            'image.image' => 'Die Datei muss ein Bild sein.',
            'image.mimes' => 'Das Bild muss im Format JPEG, PNG, JPG oder GIF sein.',
            'image.max' => 'Das Bild darf maximal 1MB groß sein.',
        ];
    }

    /**
     * Update slug when title changes
     *
     * @return void
     */
    public function updatedTitle(): void
    {
        $this->slug = Str::slug($this->title);
    }

    /**
     * Process uploaded image
     *
     * @return string|null
     */
    private function processImage(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if ($this->image instanceof \Illuminate\Http\UploadedFile || $this->image instanceof TemporaryUploadedFile) {
            $folderPath = 'teasers/' . $this->teaser->id;

            try {
                $imagePath = null;
                if ($this->image instanceof TemporaryUploadedFile) {
                    $imagePath = $this->image->store($folderPath, 'public');
                } else {
                    $imagePath = $this->image->storeAs($folderPath, $this->image->getClientOriginalName(), 'public');
                }

                Log::info('Image stored', ['path' => $imagePath]);

                // Delete old image if exists
                if ($this->teaser->image_name && $imagePath) {
                    $oldPath = $this->teaser->image_name;
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                return $imagePath;
            } catch (\Exception $e) {
                Log::error('Error storing image', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw new \Exception('Fehler beim Speichern des Bildes: ' . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Update the teaser
     *
     * @return void
     */
    public function updateTeaser(): void
    {
        $this->successMessage = '';
        $this->isLoading = true;

        try {
            // Validate form data
            $this->validate();

            // Prepare teaser data
            $teaserData = [
                'title' => $this->title,
                'description' => $this->description,
                'slug' => $this->slug,
            ];

            // Process and store the image if provided
            if ($this->image) {
                $imagePath = $this->processImage();
                if ($imagePath) {
                    $teaserData['image_name'] = $imagePath;
                }
            }

            // Update teaser in database
            $this->teaser->update($teaserData);

            Log::info('Teaser updated', ['id' => $this->teaser->id]);

            // Set success message
            $this->successMessage = 'Teaser erfolgreich aktualisiert.';

            // Notify other components
            $this->dispatch('teaser-updated', $this->teaser->id);

        } catch (ValidationException $e) {
            Log::error('Validation error', [
                'errors' => $e->errors(),
            ]);

            foreach ($e->errors() as $field => $errors) {
                foreach ($errors as $error) {
                    $this->addError($field, $error);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error updating teaser', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Ein Fehler ist aufgetreten: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Reset form fields to original values
     *
     * @return void
     */
    public function resetForm(): void
    {
        $this->loadTeaserData();
        $this->image = null;
        $this->successMessage = '';
        $this->resetErrorBag();
    }

    /**
     * Validate a property when it's updated
     *
     * @param string $propertyName
     * @return void
     */
    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Render the component
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.teasers.teaser-update', [
            'teaser' => $this->teaser  // Explicitly pass the teaser to the view
        ]);
    }
}
