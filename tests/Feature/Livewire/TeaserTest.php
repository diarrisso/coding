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
        'email' => 'admin@coding.com',
    ]);

    $this->user = User::factory()->create([
        'role' => 'USER',
        'email' => 'coding@coding.com',
    ]);

    Storage::fake('public');
    $this->timestamp = now()->timestamp;
    $filename = "teaser-image-{$this->timestamp}.jpg";
    $this->fakeImage = UploadedFile::fake()->image($filename, 640, 480);
    $this->imagePath = $this->fakeImage->store('teasers', 'public');

});

test('teaser can be created by authenticated user', function () {
    $this->actingAs($this->user);

    Livewire::test(TeaserForm::class)
        ->set('title', 'Test Teaser')
        ->set('description', 'This is a test teaser description')
        ->set('slug', 'teaser')
        ->set('user_id', $this->user->id)
        ->set('image', $this->fakeImage)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('teasers.index'));

    $teaser = Teaser::where('title', 'Test Teaser')->first();

    $this->assertNotNull($teaser);
    $this->assertEquals('This is a test teaser description', $teaser->description);
    $this->assertEquals('teaser', $teaser->slug);
    $this->assertEquals($this->user->id, $teaser->user_id);

    $this->assertNotNull($teaser->image_name);

    Storage::disk('public')->assertExists($teaser->image_name);


    $this->assertDatabaseHas('teasers', [
        'title' => 'Test Teaser',
        'description' => 'This is a test teaser description',
        'slug' => 'teaser',
        'user_id' => $this->user->id,
        'image_name' =>$teaser->image_name,
    ]);
});

test('teaser requires title description ', closure: function () {
    $this->actingAs($this->user);

    Livewire::test(TeaserForm::class)
        ->set('title', 'Te')
        ->set('description', 'This is a test teaser description')
        ->set('slug', 'teaser-slug')
        ->set('image', $this->fakeImage)
        ->call('save')
        ->assertHasErrors(['title']);


    Livewire::test(TeaserForm::class)
        ->set('title', 'Test Teaser')
        ->set('description', 'Too short')
        ->set('slug', 'teaser-slug')
        ->set('user_id', $this->user->id)
        ->set('image', $this->fakeImage)
        ->call('save')
        ->assertHasErrors(['description']);

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

     Teaser::factory()->create([
        'user_id' => $this->user->id,
        'title' => 'User Teaser',
        'description' => 'This is a user teaser',
    ]);

    Teaser::factory()->create([
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


