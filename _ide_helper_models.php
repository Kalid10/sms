<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */

namespace App\Models{
    /**
     * App\Models\AINote
     *
     * @property int $id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property string $title
     * @property string $content
     * @property int $user_id
     * @property int|null $lesson_plan_id
     * @property int|null $batch_session_id
     * @property string $model
     * @property string $source
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \App\Models\BatchSession|null $batchSession
     * @property-read \App\Models\LessonPlan|null $lessonPlan
     * @property-read \App\Models\User $user
     *
     * @method static \Illuminate\Database\Eloquent\Builder|AINote newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AINote newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AINote onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|AINote query()
     * @method static \Illuminate\Database\Eloquent\Builder|AINote whereBatchSessionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AINote whereContent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AINote whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AINote whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AINote whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AINote whereLessonPlanId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AINote whereModel($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AINote whereSource($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AINote whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AINote whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AINote whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AINote withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|AINote withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperAINote
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Absentee
     *
     * @property int $id
     * @property int $batch_session_id
     * @property int $user_id
     * @property string|null $reason
     * @property int $next_class_attended_flag
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\BatchSession $batchSession
     * @property-read \App\Models\User $user
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Absentee newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Absentee newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Absentee query()
     * @method static \Illuminate\Database\Eloquent\Builder|Absentee whereBatchSessionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Absentee whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Absentee whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Absentee whereNextClassAttendedFlag($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Absentee whereReason($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Absentee whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Absentee whereUserId($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperAbsentee
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Address
     *
     * @property int $id
     * @property string $sub_city
     * @property string $city
     * @property string $country
     * @property string|null $woreda
     * @property string $house_number
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\User|null $user
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Address query()
     * @method static \Illuminate\Database\Eloquent\Builder|Address whereCity($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Address whereCountry($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Address whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Address whereHouseNumber($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Address whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Address whereSubCity($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Address whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Address whereWoreda($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperAddress
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Admin
     *
     * @property int $id
     * @property string $position
     * @property int $user_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Announcement> $announcements
     * @property-read int|null $announcements_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SchoolYear> $schoolYear
     * @property-read int|null $school_year_count
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\AdminFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePosition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUserId($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperAdmin
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Announcement
     *
     * @property int $id
     * @property string $title
     * @property string $body
     * @property int $author_id
     * @property string $expires_on
     * @property array $target_group
     * @property array|null $target_batches
     * @property int $school_year_id
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\Admin $author
     * @property-read \App\Models\SchoolYear $schoolYear
     *
     * @method static \Database\Factories\AnnouncementFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement query()
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereAuthorId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereBody($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereExpiresOn($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereSchoolYearId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereTargetBatches($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereTargetGroup($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperAnnouncement
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Assessment
     *
     * @property int $id
     * @property string $title
     * @property string|null $description
     * @property int $maximum_point
     * @property int $assessment_type_id
     * @property int $batch_subject_id
     * @property int $quarter_id
     * @property int|null $lesson_plan_id
     * @property string $status
     * @property \Illuminate\Support\Carbon $due_date
     * @property int $created_by
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\AssessmentType $assessmentType
     * @property-read \App\Models\BatchSubject $batchSubject
     * @property-read \App\Models\User $createdBy
     * @property-read mixed $assessment_period_time
     * @property-read string $long_title
     * @property-read \App\Models\Quarter $quarter
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentAssessment> $students
     * @property-read int|null $students_count
     * @property-read \App\Models\Teacher|null $teacher
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment query()
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereAssessmentTypeId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereBatchSubjectId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereCreatedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereDueDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereLessonPlanId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereMaximumPoint($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereQuarterId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Assessment withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperAssessment
    {
    }
}

namespace App\Models{
    /**
     * App\Models\AssessmentMapping
     *
     * @property int $id
     * @property string $title
     * @property string $due_date
     * @property int $user_id
     * @property int $level_category_id
     * @property int $assessment_type_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\Assessment|null $assessment
     * @property-read \App\Models\AssessmentType $assessmentType
     * @property-read \App\Models\Level|null $level
     * @property-read \App\Models\LevelCategory $levelCategory
     * @property-read \App\Models\User $user
     *
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentMapping newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentMapping newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentMapping query()
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentMapping whereAssessmentTypeId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentMapping whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentMapping whereDueDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentMapping whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentMapping whereLevelCategoryId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentMapping whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentMapping whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentMapping whereUserId($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperAssessmentMapping
    {
    }
}

namespace App\Models{
    /**
     * App\Models\AssessmentType
     *
     * @property int $id
     * @property string $name
     * @property int $percentage
     * @property int|null $min_assessments
     * @property int|null $max_assessments
     * @property int $customizable
     * @property int $is_admin_controlled
     * @property int $school_year_id
     * @property int $level_category_id
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assessment> $assessments
     * @property-read int|null $assessments_count
     * @property-read \App\Models\LevelCategory $levelCategory
     * @property-read \App\Models\SchoolYear $schoolYear
     *
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType query()
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType whereCustomizable($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType whereIsAdminControlled($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType whereLevelCategoryId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType whereMaxAssessments($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType whereMinAssessments($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType wherePercentage($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType whereSchoolYearId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|AssessmentType withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperAssessmentType
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Batch
     *
     * @property int $id
     * @property int $level_id
     * @property int $school_year_id
     * @property string $section
     * @property int $min_students
     * @property int $max_students
     * @property \Illuminate\Support\Carbon|null $session_last_synced
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSession> $activeSession
     * @property-read int|null $active_session_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
     * @property-read int|null $activities_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assessment> $assessments
     * @property-read int|null $assessments_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $base_students
     * @property-read int|null $base_students_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subject> $base_subjects
     * @property-read int|null $base_subjects_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchGrade> $grades
     * @property-read int|null $grades_count
     * @property-read \App\Models\HomeroomTeacher|null $homeroomTeacher
     * @property-read \App\Models\Level $level
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSchedule> $schedule
     * @property-read int|null $schedule_count
     * @property-read \App\Models\SchoolYear $schoolYear
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSession> $sessions
     * @property-read int|null $sessions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchStudent> $students
     * @property-read int|null $students_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSubject> $subjects
     * @property-read int|null $subjects_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachers
     * @property-read int|null $teachers_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSession> $weeklySessions
     * @property-read int|null $weekly_sessions_count
     *
     * @method static \Database\Factories\BatchFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Batch newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Batch newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Batch onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Batch query()
     * @method static \Illuminate\Database\Eloquent\Builder|Batch whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Batch whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Batch whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Batch whereLevelId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Batch whereMaxStudents($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Batch whereMinStudents($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Batch whereSchoolYearId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Batch whereSection($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Batch whereSessionLastSynced($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Batch whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Batch withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Batch withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperBatch
    {
    }
}

namespace App\Models{
    /**
     * App\Models\BatchGrade
     *
     * @property int $id
     * @property int $batch_id
     * @property int $gradable_id
     * @property string $gradable_type
     * @property int|null $grade_scale_id
     * @property float|null $score
     * @property int|null $rank
     * @property string|null $conduct
     * @property int|null $attendance
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\Batch $batch
     *
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade query()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade whereAttendance($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade whereBatchId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade whereConduct($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade whereGradableId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade whereGradableType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade whereGradeScaleId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade whereRank($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade whereScore($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchGrade whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperBatchGrade
    {
    }
}

namespace App\Models{
    /**
     * App\Models\BatchSchedule
     *
     * @property int $id
     * @property int $school_period_id
     * @property int|null $batch_subject_id
     * @property int|null $batch_id
     * @property string $day_of_week
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\Batch|null $batch
     * @property-read \App\Models\BatchSubject|null $batchSubject
     * @property-read \App\Models\BatchSession|null $lastSession
     * @property-read \App\Models\BatchSession|null $nextSession
     * @property-read \App\Models\SchoolPeriod $schoolPeriod
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSession> $sessions
     * @property-read int|null $sessions_count
     * @property-read \App\Models\Teacher|null $teacher
     *
     * @method static \Database\Factories\BatchScheduleFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSchedule newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSchedule newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSchedule query()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSchedule whereBatchId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSchedule whereBatchSubjectId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSchedule whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSchedule whereDayOfWeek($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSchedule whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSchedule whereSchoolPeriodId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSchedule whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperBatchSchedule
    {
    }
}

namespace App\Models{
    /**
     * App\Models\BatchScheduleConfig
     *
     * @property int $id
     * @property int $school_year_id
     * @property int $max_periods_per_day
     * @property int $max_periods_per_week
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|BatchScheduleConfig newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchScheduleConfig newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchScheduleConfig onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchScheduleConfig query()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchScheduleConfig whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchScheduleConfig whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchScheduleConfig whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchScheduleConfig whereMaxPeriodsPerDay($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchScheduleConfig whereMaxPeriodsPerWeek($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchScheduleConfig whereSchoolYearId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchScheduleConfig whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchScheduleConfig withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchScheduleConfig withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperBatchScheduleConfig
    {
    }
}

namespace App\Models{
    /**
     * App\Models\BatchSession
     *
     * @property int $id
     * @property \Illuminate\Support\Carbon $date
     * @property int $batch_schedule_id
     * @property int|null $teacher_id
     * @property int|null $lesson_plan_id
     * @property string $status
     * @property int $quarter_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Absentee> $absentees
     * @property-read int|null $absentees_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AINote> $aiNotes
     * @property-read int|null $ai_notes_count
     * @property-read \App\Models\BatchSchedule $batchSchedule
     * @property-read \App\Models\BatchSubject|null $batchSubject
     * @property-read \App\Models\LessonPlan|null $lessonPlan
     * @property-read \App\Models\SchoolPeriod|null $schoolPeriod
     * @property-read \App\Models\Teacher|null $teacher
     *
     * @method static \Database\Factories\BatchSessionFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSession newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSession newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSession query()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSession whereBatchScheduleId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSession whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSession whereDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSession whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSession whereLessonPlanId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSession whereQuarterId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSession whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSession whereTeacherId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSession whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperBatchSession
    {
    }
}

namespace App\Models{
    /**
     * App\Models\BatchStudent
     *
     * @property int $id
     * @property int $batch_id
     * @property int $student_id
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
     * @property-read int|null $activities_count
     * @property-read \App\Models\Batch $batch
     * @property-read \App\Models\Level|null $level
     * @property-read \App\Models\SchoolYear|null $schoolYear
     * @property-read \App\Models\Student $student
     *
     * @method static \Illuminate\Database\Eloquent\Builder|BatchStudent newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchStudent newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchStudent onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchStudent query()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchStudent whereBatchId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchStudent whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchStudent whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchStudent whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchStudent whereStudentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchStudent whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchStudent withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchStudent withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperBatchStudent
    {
    }
}

namespace App\Models{
    /**
     * App\Models\BatchSubject
     *
     * @property int $id
     * @property int $batch_id
     * @property int $subject_id
     * @property int|null $teacher_id
     * @property int|null $weekly_frequency
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assessment> $assessments
     * @property-read int|null $assessments_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentAssessmentsGrade> $assessmentsGrades
     * @property-read int|null $assessments_grades_count
     * @property-read \App\Models\Batch $batch
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSubjectGrade> $batchGrades
     * @property-read int|null $batch_grades_count
     * @property-read \App\Models\Flag|null $flag
     * @property-read \App\Models\BatchSession|null $lastSession
     * @property-read \App\Models\Level|null $level
     * @property-read \App\Models\BatchSession|null $nextSession
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSchedule> $schedule
     * @property-read int|null $schedule_count
     * @property-read \App\Models\SchoolYear|null $schoolYear
     * @property-read \App\Models\BatchSession|null $sessions
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentSubjectGrade> $studentGrades
     * @property-read int|null $student_grades_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
     * @property-read int|null $students_count
     * @property-read \App\Models\Subject $subject
     * @property-read \App\Models\Teacher|null $teacher
     *
     * @method static \Database\Factories\BatchSubjectFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubject newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubject newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubject query()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubject whereBatchId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubject whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubject whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubject whereSubjectId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubject whereTeacherId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubject whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubject whereWeeklyFrequency($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperBatchSubject
    {
    }
}

namespace App\Models{
    /**
     * App\Models\BatchSubjectGrade
     *
     * @property int $id
     * @property int $batch_id
     * @property int $batch_subject_id
     * @property int $gradable_id
     * @property string $gradable_type
     * @property int|null $grade_scale_id
     * @property float|null $score
     * @property int|null $rank
     * @property string|null $conduct
     * @property int|null $attendance
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade query()
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade whereAttendance($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade whereBatchId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade whereBatchSubjectId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade whereConduct($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade whereGradableId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade whereGradableType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade whereGradeScaleId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade whereRank($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade whereScore($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BatchSubjectGrade whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperBatchSubjectGrade
    {
    }
}

namespace App\Models{
    /**
     * App\Models\ChFavorite
     *
     * @property string $id
     * @property int $user_id
     * @property int $favorite_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite query()
     * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite whereFavoriteId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite whereUserId($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperChFavorite
    {
    }
}

namespace App\Models{
    /**
     * App\Models\ChMessage
     *
     * @property string $id
     * @property int $from_id
     * @property int $to_id
     * @property string|null $body
     * @property string|null $attachment
     * @property int $seen
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\User|null $from
     * @property-read \App\Models\User|null $to
     *
     * @method static \Illuminate\Database\Eloquent\Builder|ChMessage newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ChMessage newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ChMessage query()
     * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereAttachment($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereBody($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereFromId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereSeen($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereToId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChMessage whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperChMessage
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Fee
     *
     * @property int $id
     * @property string $name
     * @property string $description
     * @property float $amount
     * @property string $currency
     * @property string $status
     * @property string $target_user_type
     * @property string $due_date
     * @property int $feeable_id
     * @property string $feeable_type
     * @property mixed|null $details
     * @property int|null $penalty_id
     * @property int $level_category_id
     * @property int $is_student_tuition_fee
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $feeable
     * @property-read \App\Models\LevelCategory $levelCategory
     * @property-read \App\Models\Penalty|null $penalty
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentTuition> $studentsTuition
     * @property-read int|null $students_tuition_count
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Fee newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Fee newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Fee onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Fee query()
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereCurrency($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereDetails($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereDueDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereFeeableId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereFeeableType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereIsStudentTuitionFee($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereLevelCategoryId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee wherePenaltyId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereTargetUserType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Fee withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Fee withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperFee
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Flag
     *
     * @property int $id
     * @property int $flaggable_id
     * @property string $flaggable_type
     * @property array $type
     * @property string|null $description
     * @property int|null $batch_subject_id
     * @property int|null $flagged_by
     * @property string|null $expires_at
     * @property int $quarter_id
     * @property int|null $is_homeroom
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\BatchSubject|null $batchSubject
     * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $flaggable
     * @property-read \App\Models\User|null $flaggedBy
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Flag newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Flag newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Flag onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Flag query()
     * @method static \Illuminate\Database\Eloquent\Builder|Flag whereBatchSubjectId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Flag whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Flag whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Flag whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Flag whereExpiresAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Flag whereFlaggableId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Flag whereFlaggableType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Flag whereFlaggedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Flag whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Flag whereIsHomeroom($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Flag whereQuarterId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Flag whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Flag whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Flag withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Flag withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperFlag
    {
    }
}

namespace App\Models{
    /**
     * App\Models\GradeScale
     *
     * @property int $id
     * @property int $school_year_id
     * @property string $label
     * @property int $minimum_score
     * @property string $state
     * @property string|null $description
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\SchoolYear $schoolYear
     *
     * @method static \Database\Factories\GradeScaleFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|GradeScale newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|GradeScale newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|GradeScale query()
     * @method static \Illuminate\Database\Eloquent\Builder|GradeScale whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GradeScale whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GradeScale whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GradeScale whereLabel($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GradeScale whereMinimumScore($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GradeScale whereSchoolYearId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GradeScale whereState($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GradeScale whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperGradeScale
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Guardian
     *
     * @property int $id
     * @property int $user_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $children
     * @property-read int|null $children_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentTuition> $studentsTuition
     * @property-read int|null $students_tuition_count
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\GuardianFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Guardian newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Guardian newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Guardian query()
     * @method static \Illuminate\Database\Eloquent\Builder|Guardian whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Guardian whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Guardian whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Guardian whereUserId($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperGuardian
    {
    }
}

namespace App\Models{
    /**
     * App\Models\HomeroomTeacher
     *
     * @property int $id
     * @property int $teacher_id
     * @property int $batch_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
     * @property-read int|null $activities_count
     * @property-read \App\Models\Batch $batch
     * @property-read \App\Models\Teacher $teacher
     *
     * @method static \Database\Factories\HomeroomTeacherFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|HomeroomTeacher newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|HomeroomTeacher newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|HomeroomTeacher query()
     * @method static \Illuminate\Database\Eloquent\Builder|HomeroomTeacher whereBatchId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|HomeroomTeacher whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|HomeroomTeacher whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|HomeroomTeacher whereTeacherId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|HomeroomTeacher whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperHomeroomTeacher
    {
    }
}

namespace App\Models{
    /**
     * App\Models\InventoryCheckInOut
     *
     * @property int $id
     * @property int $inventory_item_id
     * @property int $quantity
     * @property string $status
     * @property string $check_out_date
     * @property string|null $check_in_date
     * @property string $state
     * @property int $recipient_user_id
     * @property int $provider_user_id
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\InventoryItem $item
     * @property-read \App\Models\User $provider
     * @property-read \App\Models\User $recipient
     *
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut query()
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut whereCheckInDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut whereCheckOutDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut whereInventoryItemId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut whereProviderUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut whereQuantity($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut whereRecipientUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut whereState($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryCheckInOut withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperInventoryCheckInOut
    {
    }
}

namespace App\Models{
    /**
     * App\Models\InventoryItem
     *
     * @property int $id
     * @property string $name
     * @property string|null $description
     * @property int $quantity
     * @property int $is_returnable
     * @property string $date
     * @property int $low_stock_threshold
     * @property string $visibility
     * @property int $added_by_user_id
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem query()
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereAddedByUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereIsReturnable($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereLowStockThreshold($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereQuantity($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereVisibility($value)
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperInventoryItem
    {
    }
}

namespace App\Models{
    /**
     * App\Models\LessonPlan
     *
     * @property int $id
     * @property string $topic
     * @property string $description
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
     * @property-read int|null $activities_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AINote> $aiNotes
     * @property-read int|null $ai_notes_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSession> $batchSessions
     * @property-read int|null $batch_sessions_count
     *
     * @method static \Database\Factories\LessonPlanFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|LessonPlan newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|LessonPlan newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|LessonPlan query()
     * @method static \Illuminate\Database\Eloquent\Builder|LessonPlan whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LessonPlan whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LessonPlan whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LessonPlan whereTopic($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LessonPlan whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperLessonPlan
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Level
     *
     * @property int $id
     * @property string $name
     * @property int $level_category_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Batch> $activeBatches
     * @property-read int|null $active_batches_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssessmentMapping> $assessmentMappings
     * @property-read int|null $assessment_mappings_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Batch> $batches
     * @property-read int|null $batches_count
     * @property-read \App\Models\LevelCategory $levelCategory
     *
     * @method static \Database\Factories\LevelFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Level newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Level newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Level query()
     * @method static \Illuminate\Database\Eloquent\Builder|Level whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Level whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Level whereLevelCategoryId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Level whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Level whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperLevel
    {
    }
}

namespace App\Models{
    /**
     * App\Models\LevelCategory
     *
     * @property int $id
     * @property string $name
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssessmentMapping> $assessmentMappings
     * @property-read int|null $assessment_mappings_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssessmentType> $assessmentTypes
     * @property-read int|null $assessment_types_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Level> $levels
     * @property-read int|null $levels_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SchoolPeriod> $schoolPeriods
     * @property-read int|null $school_periods_count
     *
     * @method static \Database\Factories\LevelCategoryFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|LevelCategory newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|LevelCategory newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|LevelCategory query()
     * @method static \Illuminate\Database\Eloquent\Builder|LevelCategory whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LevelCategory whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LevelCategory whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LevelCategory whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperLevelCategory
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Note
     *
     * @property int $id
     * @property int $author_id
     * @property int $entity_id
     * @property int|null $batch_session_id
     * @property string $body
     * @property string $title
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\User $author
     * @property-read \App\Models\BatchSession|null $batchSession
     * @property-read \App\Models\User $entity
     *
     * @method static \Database\Factories\NoteFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Note newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Note newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Note query()
     * @method static \Illuminate\Database\Eloquent\Builder|Note whereAuthorId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Note whereBatchSessionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Note whereBody($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Note whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Note whereEntityId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Note whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Note whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Note whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperNote
    {
    }
}

namespace App\Models{
    /**
     * App\Models\PaymentProvider
     *
     * @property int $id
     * @property string $name
     * @property string|null $logo
     * @property int $is_enabled
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentTuition> $studentTuitions
     * @property-read int|null $student_tuitions_count
     *
     * @method static \Illuminate\Database\Eloquent\Builder|PaymentProvider newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|PaymentProvider newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|PaymentProvider onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|PaymentProvider query()
     * @method static \Illuminate\Database\Eloquent\Builder|PaymentProvider whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PaymentProvider whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PaymentProvider whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PaymentProvider whereIsEnabled($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PaymentProvider whereLogo($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PaymentProvider whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PaymentProvider whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PaymentProvider withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|PaymentProvider withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperPaymentProvider
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Penalty
     *
     * @property int $id
     * @property string $type
     * @property float $amount
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Penalty newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Penalty newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Penalty onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Penalty query()
     * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Penalty withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Penalty withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperPenalty
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Quarter
     *
     * @property int $id
     * @property string $name
     * @property string|null $start_date
     * @property string|null $end_date
     * @property int $semester_id
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assessment> $assessments
     * @property-read int|null $assessments_count
     * @property-read \App\Models\SchoolYear|null $schoolYear
     * @property-read \App\Models\Semester $semester
     *
     * @method static \Database\Factories\QuarterFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter query()
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter whereEndDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter whereSemesterId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter whereStartDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Quarter withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperQuarter
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Question
     *
     * @property int $id
     * @property array $questions
     * @property int $batch_subject_id
     * @property int $user_id
     * @property int $assessment_type_id
     * @property array|null $lesson_plan_ids
     * @property string|null $topic
     * @property string $category
     * @property int $no_of_questions
     * @property int $difficulty_level
     * @property string|null $input
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\AssessmentType $assessmentType
     * @property-read \App\Models\BatchSubject $batchSubject
     * @property-read \App\Models\User $user
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Question newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Question newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Question onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Question query()
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereAssessmentTypeId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereBatchSubjectId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereCategory($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereDifficultyLevel($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereInput($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereLessonPlanIds($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereNoOfQuestions($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereQuestions($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereTopic($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Question withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Question withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperQuestion
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Role
     *
     * @property string $name
     * @property string|null $description
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
     * @property-read int|null $users_count
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Role onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Role query()
     * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Role whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Role whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Role withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Role withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperRole
    {
    }
}

namespace App\Models{
    /**
     * App\Models\SchoolPeriod
     *
     * @property int $id
     * @property string $name
     * @property string $start_time
     * @property int $duration
     * @property int $is_custom
     * @property int $school_year_id
     * @property int $level_category_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read int $order
     * @property-read \App\Models\LevelCategory $levelCategory
     * @property-read \App\Models\SchoolYear $schoolYear
     *
     * @method static \Database\Factories\SchoolPeriodFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolPeriod newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolPeriod newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolPeriod query()
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolPeriod whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolPeriod whereDuration($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolPeriod whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolPeriod whereIsCustom($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolPeriod whereLevelCategoryId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolPeriod whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolPeriod whereSchoolYearId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolPeriod whereStartTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolPeriod whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperSchoolPeriod
    {
    }
}

namespace App\Models{
    /**
     * App\Models\SchoolSchedule
     *
     * @property int $id
     * @property string $title
     * @property string|null $body
     * @property string $start_date
     * @property string|null $end_date
     * @property string $type
     * @property array|null $tags
     * @property int $school_year_id
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\SchoolYear $schoolYear
     *
     * @method static \Database\Factories\SchoolScheduleFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule query()
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule whereBody($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule whereEndDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule whereSchoolYearId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule whereStartDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule whereTags($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolSchedule withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperSchoolSchedule
    {
    }
}

namespace App\Models{
    /**
     * App\Models\SchoolYear
     *
     * @property int $id
     * @property string $start_date
     * @property string|null $end_date
     * @property string $name
     * @property int $is_ready
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SchoolSchedule> $activeSchoolSchedules
     * @property-read int|null $active_school_schedules_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
     * @property-read int|null $activities_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Announcement> $announcements
     * @property-read int|null $announcements_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssessmentType> $assessmentTypes
     * @property-read int|null $assessment_types_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Batch> $batches
     * @property-read int|null $batches_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quarter> $quarters
     * @property-read int|null $quarters_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SchoolPeriod> $schoolPeriods
     * @property-read int|null $school_periods_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SchoolSchedule> $schoolSchedules
     * @property-read int|null $school_schedules_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Semester> $semesters
     * @property-read int|null $semesters_count
     *
     * @method static \Database\Factories\SchoolYearFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear query()
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear whereEndDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear whereIsReady($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear whereStartDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|SchoolYear withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperSchoolYear
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Semester
     *
     * @property int $id
     * @property string $name
     * @property string $status
     * @property string|null $start_date
     * @property string|null $end_date
     * @property int $school_year_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
     * @property-read int|null $activities_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quarter> $quarters
     * @property-read int|null $quarters_count
     * @property-read \App\Models\SchoolYear $schoolYear
     *
     * @method static \Database\Factories\SemesterFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Semester newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Semester newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Semester onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Semester query()
     * @method static \Illuminate\Database\Eloquent\Builder|Semester whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Semester whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Semester whereEndDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Semester whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Semester whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Semester whereSchoolYearId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Semester whereStartDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Semester whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Semester whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Semester withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Semester withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperSemester
    {
    }
}

namespace App\Models{
    /**
     * App\Models\StaffAbsentee
     *
     * @property int $id
     * @property int|null $batch_session_id
     * @property int $user_id
     * @property string|null $reason
     * @property string $type
     * @property int $is_leave
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\BatchSession|null $batchSession
     * @property-read \App\Models\User $user
     *
     * @method static \Illuminate\Database\Eloquent\Builder|StaffAbsentee newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StaffAbsentee newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StaffAbsentee query()
     * @method static \Illuminate\Database\Eloquent\Builder|StaffAbsentee whereBatchSessionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StaffAbsentee whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StaffAbsentee whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StaffAbsentee whereIsLeave($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StaffAbsentee whereReason($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StaffAbsentee whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StaffAbsentee whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StaffAbsentee whereUserId($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperStaffAbsentee
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Student
     *
     * @property int $id
     * @property int $user_id
     * @property int $guardian_id
     * @property string|null $guardian_relation
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentAssessment> $assessments
     * @property-read int|null $assessments_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentAssessmentsGrade> $assessmentsGrades
     * @property-read int|null $assessments_grades_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Batch> $base_batches
     * @property-read int|null $base_batches_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSession> $batchSessions
     * @property-read int|null $batch_sessions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSubject> $batchSubjects
     * @property-read int|null $batch_subjects_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchStudent> $batches
     * @property-read int|null $batches_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Batch> $currentBatch
     * @property-read int|null $current_batch_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Flag> $flags
     * @property-read int|null $flags_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentGrade> $grades
     * @property-read int|null $grades_count
     * @property-read \App\Models\Guardian $guardian
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentNote> $notes
     * @property-read int|null $notes_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentSubjectGrade> $studentSubjectGrades
     * @property-read int|null $student_subject_grades_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentSubjectGrade> $subjectGrades
     * @property-read int|null $subject_grades_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subject> $subjects
     * @property-read int|null $subjects_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentTuition> $tuitionFee
     * @property-read int|null $tuition_fee_count
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\StudentFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Student newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Student newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Student query()
     * @method static \Illuminate\Database\Eloquent\Builder|Student whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Student whereGuardianId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Student whereGuardianRelation($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Student whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Student whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Student whereUserId($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperStudent
    {
    }
}

namespace App\Models{
    /**
     * App\Models\StudentAssessment
     *
     * @property int $id
     * @property int $assessment_id
     * @property int $student_id
     * @property int|null $point
     * @property string|null $comment
     * @property string|null $status
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\Assessment $assessment
     * @property-read \App\Models\Student $student
     *
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment query()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment whereAssessmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment whereComment($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment wherePoint($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment whereStudentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessment withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperStudentAssessment
    {
    }
}

namespace App\Models{
    /**
     * App\Models\StudentAssessmentsGrade
     *
     * @property int $id
     * @property int $student_id
     * @property int $assessment_type_id
     * @property int $batch_subject_id
     * @property int $gradable_id
     * @property string $gradable_type
     * @property int $grade_scale_id
     * @property float $score
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\AssessmentType $assessmentType
     * @property-read \App\Models\BatchSubject $batchSubject
     * @property-read \App\Models\GradeScale $gradeScale
     * @property-read \App\Models\Student $student
     *
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessmentsGrade newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessmentsGrade newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessmentsGrade query()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessmentsGrade whereAssessmentTypeId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessmentsGrade whereBatchSubjectId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessmentsGrade whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessmentsGrade whereGradableId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessmentsGrade whereGradableType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessmentsGrade whereGradeScaleId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessmentsGrade whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessmentsGrade whereScore($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessmentsGrade whereStudentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentAssessmentsGrade whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperStudentAssessmentsGrade
    {
    }
}

namespace App\Models{
    /**
     * App\Models\StudentGrade
     *
     * @property int $id
     * @property int $student_id
     * @property int $gradable_id
     * @property string $gradable_type
     * @property int|null $grade_scale_id
     * @property float|null $score
     * @property float|null $total_score
     * @property int|null $rank
     * @property string|null $conduct
     * @property int|null $attendance
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $gradable
     * @property-read \App\Models\GradeScale|null $gradeScale
     * @property-read \App\Models\Student $student
     *
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade query()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade whereAttendance($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade whereConduct($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade whereGradableId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade whereGradableType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade whereGradeScaleId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade whereRank($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade whereScore($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade whereStudentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade whereTotalScore($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentGrade whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperStudentGrade
    {
    }
}

namespace App\Models{
    /**
     * App\Models\StudentNote
     *
     * @property int $id
     * @property string $title
     * @property string|null $description
     * @property int $student_id
     * @property int $author_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\User $author
     * @property-read \App\Models\Student $student
     *
     * @method static \Illuminate\Database\Eloquent\Builder|StudentNote newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentNote newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentNote query()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereAuthorId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereStudentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentNote whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperStudentNote
    {
    }
}

namespace App\Models{
    /**
     * App\Models\StudentSubjectGrade
     *
     * @property int $id
     * @property int $student_id
     * @property int $batch_subject_id
     * @property int $gradable_id
     * @property string $gradable_type
     * @property int|null $grade_scale_id
     * @property float|null $score
     * @property float|null $total_score
     * @property int|null $rank
     * @property string|null $conduct
     * @property int|null $attendance
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentAssessmentsGrade> $assessmentsGrade
     * @property-read int|null $assessments_grade_count
     * @property-read \App\Models\BatchSubject $batchSubject
     * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $gradable
     * @property-read \App\Models\GradeScale|null $gradeScale
     * @property-read \App\Models\Student $student
     *
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade query()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade whereAttendance($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade whereBatchSubjectId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade whereConduct($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade whereGradableId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade whereGradableType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade whereGradeScaleId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade whereRank($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade whereScore($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade whereStudentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade whereTotalScore($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentSubjectGrade whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperStudentSubjectGrade
    {
    }
}

namespace App\Models{
    /**
     * App\Models\StudentTuition
     *
     * @property int $id
     * @property int $student_id
     * @property int $fee_id
     * @property int|null $payment_provider_id
     * @property float $amount
     * @property string $status
     * @property string|null $paid_at
     * @property string|null $transaction_id
     * @property mixed|null $details
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\Fee $fee
     * @property-read \App\Models\PaymentProvider|null $paymentProvider
     * @property-read \App\Models\StudentTuitionPenalty|null $penalty
     * @property-read \App\Models\Student $student
     *
     * @method static \Database\Factories\StudentTuitionFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition query()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition whereAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition whereDetails($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition whereFeeId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition wherePaidAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition wherePaymentProviderId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition whereStudentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition whereTransactionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuition withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperStudentTuition
    {
    }
}

namespace App\Models{
    /**
     * App\Models\StudentTuitionPenalty
     *
     * @property int $id
     * @property int $student_tuition_id
     * @property int $penalty_id
     * @property float $amount
     * @property string|null $deleted_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\StudentTuition $studentTuition
     *
     * @method static \Database\Factories\StudentTuitionPenaltyFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuitionPenalty newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuitionPenalty newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuitionPenalty query()
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuitionPenalty whereAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuitionPenalty whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuitionPenalty whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuitionPenalty whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuitionPenalty wherePenaltyId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuitionPenalty whereStudentTuitionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StudentTuitionPenalty whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperStudentTuitionPenalty
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Subject
     *
     * @property int $id
     * @property string $full_name
     * @property string $short_name
     * @property string $category
     * @property array|null $tags
     * @property int $priority
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Batch> $activeBatches
     * @property-read int|null $active_batches_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
     * @property-read int|null $activities_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Batch> $batches
     * @property-read int|null $batches_count
     *
     * @method static \Database\Factories\SubjectFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Subject newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Subject newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Subject onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Subject query()
     * @method static \Illuminate\Database\Eloquent\Builder|Subject whereCategory($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Subject whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Subject whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Subject whereFullName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Subject whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Subject wherePriority($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Subject whereShortName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Subject whereTags($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Subject whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Subject withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Subject withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperSubject
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Teacher
     *
     * @property int $id
     * @property int $user_id
     * @property array|null $leave_info
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSubject> $activeBatchSubjects
     * @property-read int|null $active_batch_subjects_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Batch> $activeBatches
     * @property-read int|null $active_batches_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assessment> $assessments
     * @property-read int|null $assessments_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSchedule> $batchSchedules
     * @property-read int|null $batch_schedules_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSession> $batchSessions
     * @property-read int|null $batch_sessions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSubject> $batchSubjects
     * @property-read int|null $batch_subjects_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TeacherFeedback> $feedbacks
     * @property-read int|null $feedbacks_count
     * @property-read int $active_weekly_sessions
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HomeroomTeacher> $homeroom
     * @property-read int|null $homeroom_count
     * @property-read \App\Models\BatchSession|null $inProgressBatchSession
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BatchSession> $lessonPlans
     * @property-read int|null $lesson_plans_count
     * @property-read \App\Models\BatchSession|null $nextBatchSession
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StaffAbsentee> $staffAbsentee
     * @property-read int|null $staff_absentee_count
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\TeacherFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Teacher newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Teacher newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Teacher query()
     * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereLeaveInfo($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereUserId($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperTeacher
    {
    }
}

namespace App\Models{
    /**
     * App\Models\TeacherFeedback
     *
     * @property int $id
     * @property int $teacher_id
     * @property int $author_id
     * @property string $feedback
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\User $author
     * @property-read \App\Models\Teacher $teacher
     *
     * @method static \Database\Factories\TeacherFeedbackFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|TeacherFeedback newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|TeacherFeedback newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|TeacherFeedback query()
     * @method static \Illuminate\Database\Eloquent\Builder|TeacherFeedback whereAuthorId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TeacherFeedback whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TeacherFeedback whereFeedback($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TeacherFeedback whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TeacherFeedback whereTeacherId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TeacherFeedback whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperTeacherFeedback
    {
    }
}

namespace App\Models{
    /**
     * App\Models\User
     *
     * @property int $id
     * @property string $name
     * @property string|null $email
     * @property string|null $username
     * @property string|null $phone_number
     * @property string|null $profile_image
     * @property string $gender
     * @property \Illuminate\Support\Carbon|null $date_of_birth
     * @property string $type
     * @property int $is_blocked
     * @property int|null $address_id
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property array|null $fcm_tokens
     * @property string $password
     * @property int $active_status
     * @property string $avatar
     * @property int $dark_mode
     * @property string|null $messenger_color
     * @property int $openai_daily_usage
     * @property string|null $remember_token
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Absentee> $absentee
     * @property-read int|null $absentee_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
     * @property-read int|null $activities_count
     * @property-read \App\Models\Address|null $address
     * @property-read \App\Models\Admin|null $admin
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssessmentMapping> $assessmentMapping
     * @property-read int|null $assessment_mapping_count
     * @property-read \App\Models\Guardian|null $guardian
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
     * @property-read int|null $roles_count
     * @property-read \App\Models\Student|null $student
     * @property-read \App\Models\Teacher|null $teacher
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     *
     * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User query()
     * @method static \Illuminate\Database\Eloquent\Builder|User whereActiveStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereAddressId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereDarkMode($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereDateOfBirth($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereFcmTokens($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereIsBlocked($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereMessengerColor($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereOpenaiDailyUsage($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNumber($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereProfileImage($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperUser
    {
    }
}

namespace App\Models{
    /**
     * App\Models\UserPosition
     *
     * @property int $id
     * @property string $name
     * @property string|null $description
     * @property mixed $role_names
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
     * @property-read int|null $activities_count
     *
     * @method static \Illuminate\Database\Eloquent\Builder|UserPosition newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserPosition newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserPosition query()
     * @method static \Illuminate\Database\Eloquent\Builder|UserPosition whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPosition whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPosition whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPosition whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPosition whereRoleNames($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPosition whereUpdatedAt($value)
     *
     * @mixin \Eloquent
     */
    class IdeHelperUserPosition
    {
    }
}

namespace App\Models{
    /**
     * App\Models\UserRole
     *
     * @property int $id
     * @property string $role_name
     * @property int $user_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
     * @property-read int|null $activities_count
     *
     * @method static \Illuminate\Database\Eloquent\Builder|UserRole newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserRole newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserRole onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|UserRole query()
     * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereRoleName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserRole withTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|UserRole withoutTrashed()
     *
     * @mixin \Eloquent
     */
    class IdeHelperUserRole
    {
    }
}
