<?php

namespace App\Livewire;

use App\Models\Image;
use App\Models\Teaser;
use App\Services\ImageService;
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
 * TeaserForm Component
 *
 * This component handles the creation and editing of teasers.
 */
class TeaserForm extends Component
{
    use WithFileUploads;

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

    /** @var int|null The ID of the user who owns the teaser */
    public ?int $user_id;

    /** @var \App\Models\Teaser|null The teaser being edited */
    public ?Teaser $teaser = null;

    /** @var int|null The ID of the teaser being edited */
    public ?int $teaserId = null;

    /** @var bool Whether the form is in edit mode */
    public bool $isEditMode = false;

    /** @var bool Whether the form is currently loading/processing */
    public bool $isLoading = false;

    /** @var array The event listeners for this component */
    protected $listeners = [
        'editTeaser' => 'editTeaser',
    ];
    /**
     * Check if the user is authenticated
     *
     * @return bool
     */
    private function checkAuthentication(): bool
    {
        if (!Auth::check()) {
            session()->flash('error', 'Sie müssen angemeldet sein, um auf diese Seite zuzugreifen.');
            $this->redirect(route('login'));
            return false;
        }

        $this->user_id = Auth::id();
        return true;
    }

    /**
     * Check if the user is authorized to edit the teaser
     *
     * @param Teaser $teaser
     * @return bool
     */
    private function checkAuthorization(Teaser $teaser): bool
    {
        if ($teaser->user_id !== Auth::id()) {
            session()->flash('error', 'Sie sind nicht berechtigt, diesen Teaser zu bearbeiten.');
            $this->redirect(route('teasers.index'));
            return false;
        }

        return true;
    }

    /**
     * Load teaser data into the component
     *
     * @param Teaser $teaser
     * @return void
     */
    private function loadTeaserData(Teaser $teaser): void
    {
        $this->teaser = $teaser;
        $this->teaserId = $teaser->id;
        $this->title = $teaser->title;
        $this->description = $teaser->description;
        $this->slug = $teaser->slug;
        $this->image_name = $teaser->image_name;
        $this->user_id = $teaser->user_id;
        $this->isEditMode = true;
    }


    public function mount(?Teaser $teaser = null): void
    {
        $this->isLoading = false;

        Log::info('TeaserForm mount', [
            'route_name' => request()->route()->getName(),
            'is_create_route' => request()->routeIs('teasers.create'),
            'teaser_exists' => isset($teaser) && $teaser->exists,
            'teaser_id' => $teaser?->id,
            'user_id' => Auth::id(),
            'teaser_user_id' => $teaser?->user_id,
        ]);

        if (!$this->checkAuthentication()) {
            return;
        }

        if (!$teaser || !$teaser->exists) {
            $this->isEditMode = false;
            return;
        }

        if (!$this->checkAuthorization($teaser)) {
            return;
        }

        $this->loadTeaserData($teaser);
    }

    /**
     * Find a teaser by ID
     *
     * @param int $teaserId
     * @return Teaser|null
     */
    private function findTeaser(int $teaserId): ?Teaser
    {
        try {
            return Teaser::findOrFail($teaserId);
        } catch (\Exception $e) {
            session()->flash('error', 'Der angeforderte Teaser existiert nicht.');
            $this->redirect(route('teasers.index'));
            return null;
        }
    }

    /**
     * Delete teaser image from storage
     *
     * @param Teaser $teaser
     * @return void
     */
    private function deleteImage(Teaser $teaser): void
    {
        if ($teaser->image_name) {
            $path = 'teasers/' . $teaser->id . '/' . $teaser->image_name;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    /**
     * Reset form fields
     *
     * @return void
     */
    private function resetForm(): void
    {
        $this->reset(['title', 'description', 'slug', 'image_name', 'teaser', 'teaserId', 'isEditMode']);
        $this->isLoading = false;
    }

    /**
     * Reset image selection
     *
     * @return void
     */
    public function resetImage(): void
    {
        $this->reset(['image']);
    }

    /**
     * Edit a teaser.
     *
     * @param int $teaserId The ID of the teaser to edit
     * @return void
     */
    public function editTeaser(int $teaserId): void
    {
        $teaser = $this->findTeaser($teaserId);
        if (!$teaser) {
            return;
        }

        if (!$this->checkAuthorization($teaser)) {
            return;
        }

        $this->mount($teaser);
    }

    /**
     * Get the validation rules for the form.
     *
     * @return array<string, string>
     */
    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:1000',
            'slug' => 'required|string|max:255',
            'image_name' =>  'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id',
        ];
    }

    /**
     * Get the validation messages for the form.
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
            'image.required' => 'Ein Bild ist erforderlich.',
            'image.image' => 'Die Datei muss ein Bild sein.',
            'image.mimes' => 'Das Bild muss im Format JPEG, PNG, JPG oder GIF sein.',
            'image.max' => 'Das Bild darf maximal 1MB groß sein.',
            'user_id.required' => 'Benutzer-ID ist erforderlich.',
            'user_id.exists' => 'Ungültige Benutzer-ID.',
        ];
    }


    /**
     * Prepare the data for validation.
     * Automatically generates a slug from the title if not provided.
     *
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    protected function prepareForValidation($attributes): array
    {
        if (empty($attributes['slug']) && !empty($attributes['title'])) {
            $attributes['slug'] = $this->generateUniqueSlug($attributes['title']);
        }

        return $attributes;
    }

    /**
     * Generate a unique slug based on the title
     *
     * @param string $title
     * @return string
     */
    protected function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;


        if ($this->isEditMode && $this->teaser && $this->teaser->slug === $slug) {
            return $slug;
        }

        while (Teaser::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }


    /**
     * Process and store the uploaded image
     *
     * @return string|null The path to the stored image
     * @throws \Exception If image storage fails
     */
    private function processImage(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (is_string($this->image)) {
            Log::info('Using existing image', ['path' => $this->image]);
            return $this->image;
        }

        if ($this->image instanceof \Illuminate\Http\UploadedFile) {
            $folderPath = 'teasers/' . time();
            if ($this->isEditMode && $this->teaser) {
                $folderPath .= '_' . $this->teaser->id;
            }

            try {
                $imagePath = null;
                if ($this->image instanceof TemporaryUploadedFile) {
                    $imagePath = $this->image->store($folderPath, 'public');
                } else {
                    $imagePath = $this->image->storeAs($folderPath, $this->image->getClientOriginalName(), 'public');
                }

                Log::info('Image stored', ['path' => $imagePath]);

                if ($this->isEditMode && $this->teaser && $this->teaser->image_name && $imagePath) {
                    Storage::disk('public')->delete($this->teaser->image_name);
                }

                return $imagePath;
            } catch (\Exception $e) {
                Log::error('Error storing image', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw new \Exception('Error saving image: ' . $e->getMessage());
            }
        }

        Log::info('No image provided or incorrect format', [
            'image' => $this->image,
            'type' => gettype($this->image),
            'class' => $this->image ? get_class($this->image) : 'N/A'
        ]);

        return null;
    }

    /**
     * Prepare teaser data for saving
     *
     * @param string|null $imagePath
     * @return array
     */
    private function prepareTeaserData(?string $imagePath): array
    {
        $teaserData = [
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'user_id' => $this->user_id,
        ];

        if ($imagePath) {
            $teaserData['image_name'] = $imagePath;
        }

        return $teaserData;
    }

    /**
     * Save or update the teaser
     *
     * @param array $teaserData
     * @return void
     */
    private function saveTeaserToDatabase(array $teaserData): void
    {
        if ($this->isEditMode && $this->teaser) {
            $this->teaser->update($teaserData);
            Log::info('Teaser updated', ['id' => $this->teaser->id]);
            session()->flash('message', 'Teaser erfolgreich aktualisiert.');
        } else {
            $teaser = Teaser::create($teaserData);
            Log::info('Teaser created', ['id' => $teaser->id]);
            session()->flash('message', 'Teaser erfolgreich erstellt.');
        }
    }

    private function handleValidationException(ValidationException $e): void
    {
        Log::error('Validation error', [
            'errors' => $e->errors(),
        ]);

        foreach ($e->errors() as $field => $errors) {
            foreach ($errors as $error) {
                $this->addError($field, $error);
            }
        }
    }

    /**
     * Save the teaser
     *
     * @return mixed
     */
    public function save()
    {
        $this->isLoading = true;
        try {
            if (!$this->checkAuthentication()) {
                $this->isLoading = false;
                return redirect(route('login'));
            }

            if (empty($this->slug) && !empty($this->title)) {
                $this->slug = $this->generateUniqueSlug($this->title);
            }

            $this->validate();


            $imagePath = $this->processImage();
            $teaserData = $this->prepareTeaserData($imagePath);

            $this->authorize('create', Teaser::class);
            $this->saveTeaserToDatabase($teaserData);


            $this->resetForm();
            $this->dispatch('teaser-created');
            $this->dispatch('refresh-teasers');

            $this->isLoading = false;
            session()->flash('isLoading', false);
            return $this->redirect(route('teasers.index'));

        } catch (ValidationException $e) {
            $this->isLoading = false;
            $this->handleValidationException($e);
            return null;
        } catch (\Exception $e) {

            session()->flash('error', 'Ein Fehler ist aufgetreten: ' . $e->getMessage());
            $this->isLoading = false;
            return null;
        }
    }


    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
    }


    public function render(): View
    {
        return view('livewire.teasers.teaser-form');
    }

}
