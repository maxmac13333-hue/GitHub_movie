<?php
namespace Tests\Feature;
use App\Models\{Movie,User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
class MoviePosterUploadTest extends TestCase
{
 use RefreshDatabase;
 public function test_admin_can_upload_replace_and_keep_a_poster(): void {
  Storage::fake('posters');
  $this->actingAs(User::factory()->create(['is_admin'=>true]));
  $this->post(route('admin.movies.store'),['Title'=>'Upload test','poster_file'=>UploadedFile::fake()->image('poster.jpg')])->assertSessionHasNoErrors();
  $movie=Movie::firstOrFail();
  Storage::disk('posters')->assertExists($movie->Poster);
  $original=$movie->Poster;
  $this->put(route('admin.movies.update',$movie),['Title'=>'Updated'])->assertSessionHasNoErrors();
  $this->assertSame($original,$movie->fresh()->Poster);
  $this->put(route('admin.movies.update',$movie),['Title'=>'Updated','poster_file'=>UploadedFile::fake()->image('new.png')])->assertSessionHasNoErrors();
  $this->assertNotSame($original,$movie->fresh()->Poster);
  Storage::disk('posters')->assertExists($movie->fresh()->Poster);
 }
 public function test_invalid_or_oversized_uploads_are_rejected(): void {
  Storage::fake('posters');
  $this->actingAs(User::factory()->create(['is_admin'=>true]));
  foreach([UploadedFile::fake()->create('bad.php',1,'text/plain'),UploadedFile::fake()->image('large.jpg')->size(2049)] as $file){
   $this->post(route('admin.movies.store'),['Title'=>'Invalid','poster_file'=>$file])->assertSessionHasErrors('poster_file');
  }
  $this->assertDatabaseCount('movie',0);
  $this->assertEmpty(Storage::disk('posters')->allFiles());
 }
}
