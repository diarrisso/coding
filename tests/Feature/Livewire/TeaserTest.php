<?php


use App\Livewire\TeaserForm;
use App\Livewire\Teasers\Create;
use App\Models\Teaser;
use App\Models\User;
use App\Notifications\TeaserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'role' => 'ADMIN',
        'email' => 'admin@example.com',
    ]);

    $this->user = User::factory()->create([
        'role' => 'USER',
        'email' => 'user@example.com',
    ]);

    Storage::fake('public');

    $fakeImage = UploadedFile::fake()->image('teaser-image.jpg', 640, 480);

    $this->fakeImage = $fakeImage;
    $this->imagePath = $fakeImage->store('teasers', 'public');


});

test('teaser can be created by authenticated user', function () {
    $this->actingAs($this->user);


    Livewire::test(TeaserForm::class)
        ->set('title', 'Test Teaser')
        ->set('description', 'This is a test teaser description')
        ->set('slug', 'teaser')
        ->set('user_id', $this->user->id)
        ->set('image_name', $this->fakeImage)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('teasers.index'));

    Storage::disk('public')->assertExists($this->imagePath);

    $this->assertDatabaseHas('teasers', [
        'title' => 'Test Teaser',
        'description' => 'This is a test teaser description',
        'slug' => 'teaser',
        'user_id' => $this->user->id,
        'image_name' =>$this->fakeImage,
    ]);
});

test('teaser requires title and content', function () {
    $this->actingAs($this->user);


    Livewire::test(Create::class)
        ->set('title', '')
        ->set('description', '')
        ->call('save')
        ->assertHasErrors(['title', 'description']);
});

test('teaser title must be at least 3 characters', function () {
    $this->actingAs($this->user);

    Livewire::test(Create::class)
        ->set('title', 'AB')
        ->set('description', 'This is valid content')
        ->call('save')
        ->assertHasErrors(['title']);
});

test('admin receives notification when teaser is created', function () {
    $this->actingAs($this->user);

    Notification::fake();

    Livewire::test(Create::class)
        ->set('title', 'Notification Test')
        ->set('description', 'This teaser should trigger a notification')
        ->set('slug', 'published')
        ->call('save');

    Notification::assertSentTo(
        $this->admin,
        TeaserNotification::class,
        function ($notification) {
            return $notification->teaser->title === 'Notification Test';
        }
    );
});

test('guest cannot access teaser creation page', function () {
    $this->get(route('teasers.create'))
        ->assertRedirect(route('login'));
});

test('user can see own teasers in listing', function () {
    $this->actingAs($this->user);

    $userTeaser = Teaser::factory()->create([
        'user_id' => $this->user->id,
        'title' => 'User Teaser',
        'description' => 'This is a user teaser',
    ]);

    $otherTeaser = Teaser::factory()->create([
        'user_id' => $this->admin->id,
        'title' => 'Admin Teaser'
    ]);

    $this->get(route('teasers.index'))
        ->assertSee('User Teaser');

    if (config('teasers.user_separation', false)) {
        $this->get(route('teasers.index'))
            ->assertDontSee('Admin Teaser');
    }
});
