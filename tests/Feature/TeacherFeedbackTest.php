<?php

use App\Models\Teacher;
use App\Models\TeacherFeedback;
use App\Models\User;

it('creates a teacher\'s feedback by a non-teacher user', function () {
    $user = User::factory()->create();
    $teacher = Teacher::factory()->create();

    $response = $this->actingAs($user)->post(route('teacher.feedback.add'), [
        'teacher_id' => $teacher->id,
        'feedback' => 'Test feedback',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Feedback added successfully.');
    $this->assertDatabaseHas('teachers_feedback', [
        'teacher_id' => $teacher->id,
        'feedback' => 'Test feedback',
    ]);
});

it('restricts teachers from creating feedback', function () {
    $user = User::factory()->create(['type' => User::TYPE_TEACHER]);
    $teacher = Teacher::factory()->create();
    $teacher->user()->associate($user);
    $teacher->save();

    $response = $this->actingAs($user)->post(route('teacher.feedback.add'), [
        'teacher_id' => $teacher->id,
        'feedback' => 'Test feedback',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error', 'You are not allowed to add feedback.');
    $this->assertDatabaseMissing('teachers_feedback', [
        'teacher_id' => $teacher->id,
        'feedback' => 'Test feedback',
    ]);
});

it('restricts teachers from updating feedback', function () {
    $teacher = Teacher::factory()->create();
    $author = User::factory()->create();
    $teacherFeedback = TeacherFeedback::factory()->create([
        'teacher_id' => $teacher->id,
        'author_id' => $author->id,
    ]);

    // Set user type to teacher
    $author->update(['type' => User::TYPE_TEACHER]);

    $response = $this->actingAs($author)
        ->post(
            route('teacher.feedback.update', ['feedback' => $teacherFeedback->id]),
            ['feedback' => 'Updated feedback']
        );

    $response->assertRedirect();
    $response->assertSessionHas('error', 'You are not allowed to update feedback.');

    $this->assertDatabaseHas('teachers_feedback', [
        'id' => $teacherFeedback->id,
        'feedback' => $teacherFeedback->feedback,
    ]);
});

it('deletes feedback', function () {
    $user = User::factory()->create(['type' => User::TYPE_ADMIN]);

    $teacher = Teacher::factory()->create();
    $author = User::factory()->create();

    $feedback = TeacherFeedback::factory()->create([
        'teacher_id' => $teacher->id,
        'author_id' => $author->id,
    ]);

    // Test deleting feedback as an admin user
    $response = $this->actingAs($user)->delete(route('teacher.feedback.delete', $feedback->id));
    $response->assertRedirect()
        ->assertSessionHas('success',
            'Feedback deleted successfully.');
    $this->assertDatabaseMissing('teachers_feedback', [
        'id' => $feedback->id,
        'feedback' => $feedback->feedback,
    ]);
});

it('updates a teacher\'s feedback by a non-teacher user', function () {
    $user = User::factory()->create();
    $teacher = Teacher::factory()->create();
    $author = User::factory()->create();

    $feedback = TeacherFeedback::factory()->create([
        'teacher_id' => $teacher->id,
        'author_id' => $author->id,
    ]);

    $response = $this->actingAs($user)->post(route('teacher.feedback.update', $feedback->id), [
        'feedback' => 'Updated feedback',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Feedback updated successfully.');
    $this->assertDatabaseHas('teachers_feedback', [
        'id' => $feedback->id,
        'feedback' => 'Updated feedback',
    ]);
});
