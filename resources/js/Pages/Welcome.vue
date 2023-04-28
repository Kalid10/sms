<template>
    <div class="">Welcome</div>
    <div class="grid grid-cols-4 gap-6">
        <button class="h-14 bg-lime-500 " @click="registerBulkUser">Register Bulk User</button>
        <button class="h-14 bg-lime-500 " @click="registerAdmin">Register Admin</button>
        <button class="h-14 bg-lime-500 " @click="registerStudent">Register Student</button>
        <input type="file" @change="handleFileUpload">
        <button class="h-14 bg-lime-500 " @click="registerTeacher">Register Teacher</button>
        <button class="h-14 bg-pink-900 text-white " @click="login">Test Login</button>
        <button class="h-14 bg-pink-900 text-white " @click="logout">Test Logout</button>
        <button class="h-14  bg-gray-600 text-white " @click="assignRoles">Test Assign Role</button>
        <button class="h-14  bg-gray-600 text-white " @click="removeRoles">Test Remove Role</button>
        <button class="h-14 bg-gray-600 text-white " @click="showAllRoles">Get Roles</button>
        <button class="h-14  bg-gray-600 text-white " @click="userDetails">User Details</button>
        <button class="h-14 bg-red-600 text-white " @click="roleActivities">Test Role Activities</button>
        <button class="h-14 bg-indigo-500 text-white " @click="addSubject">Test Add Subject</button>
        <button class="h-14 bg-indigo-500 text-white " @click="addSubjects">Test Add Subjects</button>
        <button class="h-14 bg-indigo-500 text-white " @click="deleteSubject">Test Delete Subject</button>
        <button class="h-14 bg-fuchsia-400 " @click="addSchoolYear">Test Add School Year</button>
        <button class="h-14 bg-emerald-500 " @click="addSemesters">Test Create Semester</button>
        <button class="h-14 bg-emerald-500 " @click="updateSemester">Test Update Semester</button>
        <button class="h-14 bg-emerald-500 " @click="listSemesters">Test List Semester</button>
        <button class="h-14 bg-emerald-500 " @click="deleteSemester">Test Delete Semester</button>
        <button class="h-14 bg-sky-400 " @click="addBatch">Test Add Batch</button>
        <button class="h-14 bg-sky-400 " @click="addBatches">Test Add Batches</button>
        <button class="h-14 bg-sky-400 " @click="getBatches">Test Get Batches</button>
        <button class="h-14 bg-sky-400 " @click="activeBatches">Test Get Active Batches</button>
        <button class="h-14 bg-amber-500 " @click="assignHomeRoomTeacher">Test Assign Homeroom Teacher</button>
        <button class="h-14 bg-amber-500 " @click="removeHomeRoomTeacher">Test Remove Homeroom Teacher</button>
        <button class="h-14 bg-amber-500 " @click="getHomeRoomTeachers">Test Get Homeroom Teachers</button>
        <button class="h-14 bg-cyan-400 " @click="addStudentToBatch">Test Add Students To Batch</button>
        <button class="h-14 bg-cyan-400 " @click="getBatchStudents">Test Get Batch Students</button>
        <button class="h-14 bg-cyan-400 " @click="addBatchSubjects">Test Add Batch Subjects</button>
        <button class="h-14 bg-purple-400 " @click="addLevel">Test Add Level</button>
        <button class="h-14 bg-purple-400 " @click="getLevels">Test Get Level</button>
        <button class="h-14 bg-yellow-400 " @click="assignBatchSubjectsTeachers">Test Assign Batch Subject Teachers
        </button>
        <button class="h-14 bg-orange-400 " @click="addUserPosition">Test Add User Position</button>
        <button class="h-14 bg-orange-400 " @click="updateUserPosition">Test Update User Position</button>
        <button class="h-14 bg-orange-400 " @click="deleteUserPosition">Test Delete User Position</button>
        <button class="h-14 bg-green-500 " @click="addSchoolSchedule">Test Add School Schedule</button>
        <button class="h-14 bg-green-500 " @click="updateSchoolSchedule">Test Update School Schedule</button>
        <button class="h-14 bg-green-500 " @click="getSchoolSchedules">Test Get School Schedule</button>
        <button class="h-14 bg-green-500 " @click="deleteSchoolSchedule">Test Delete School Schedule</button>
        <button class="h-14 bg-red-400 p-1" @click="getAnnouncements">Test get Announcements</button>
        <button class="h-14 bg-red-400 p-1" @click="createAnnouncement">Create Announcement</button>
        <button class="h-14 bg-red-400 p-1" @click="updateAnnouncement">Update Announcement</button>
        <button class="h-14 bg-red-400 p-1" @click="deleteAnnouncement">Delete Announcement</button>
        <button class="h-14 bg-stone-600 p-1 text-white" @click="createSchoolPeriod">Create School Period</button>
        <button class="h-14 bg-stone-600 p-1 text-white" @click="createBatchSchedule">Create Batch Schedules</button>
        <button class="h-14 bg-stone-600 p-1 text-white" @click="checkSchoolPeriod">Check Schedule</button>
        <button class="h-14 bg-fuchsia-600 p-1 text-white" @click="addStudentAbsentees">Test Add Student Absentees
        </button>
        <button class="h-14 bg-fuchsia-600 p-1 text-white" @click="getStudentAbsenteePercentage">Test Get Student
            Absentees
        </button>
        <button class="h-14 bg-blue-600 p-1 text-white" @click="batchSessions">Test Get Batch Sessions</button>
        <button class="h-14 bg-blue-600 p-1 text-white" @click="teacherSessions">Test Get Teacher Sessions</button>
        <button class="h-14 bg-yellow-400 p-1" @click="addNotes">Test Add notes</button>
        <button class="h-14 bg-yellow-400 p-1" @click="getNotes">Test Get notes</button>

        <button class="h-14 bg-orange-800 p-1 text-white" @click="addTeacherFeedback">Test Add Teacher Feedback</button>
    </div>

</template>
<script setup>
import {router} from "@inertiajs/vue3";
import {ref} from "vue";

// Listen for student import broadcast events
Echo.private('students-import')
    .listen('.students-import', (e) => {
        // Two variables are passed to the callback function
        // Check the type to see if it is success or error
        // e.message and e.type are the variables
        console.log(e.type);
        console.log(e.message);
    });

Echo.private('teachers-import')
    .listen('.teachers-import', (e) => {
        // Two variables are passed to the callback function
        // Check the type to see if it is success or error
        // e.message and e.type are the variables
        console.log(e.type);
        console.log(e.message);
    });

Echo.private('batch-schedule')
    .listen('.batch-schedule', (e) => {
        // Two variables are passed to the callback function
        // Check the type to see if it is success or error
        // e.message and e.type are the variables
        console.log(e.type);
        console.log(e.message);
    });

// Register admin
function registerAdmin() {
    router.post('/register', {
        name: "Biniyam Lemma",
        email: "admin@gmaill.com",
        position: "Principal",
        gender: "male",
        type: "admin"
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    });
}

const file = ref(null);

function handleFileUpload(event) {
    file.value = event.target.files[0];
}

function registerStudent() {
    router.post('/register', {
        name: "Kidist Andarge",
        email: "K@gmails.com",
        type: "student",
        gender: "female",
        date_of_birth: "03-07-2000",
        level_id: 1,
        guardian_name: "Kalid Abdu",
        guardian_phone_number: "0963134321",
        guardian_email: "k2@gmail.com",
        guardian_gender: 'male',
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    });
}

// Register student
function registerBulkUser() {
    router.post('/register-bulk', {
        user_file: file.value,
        user_type: "student",
    });
}

// Register teacher
function registerTeacher() {
    router.post('/register', {
        name: "Yoseph Seboka",
        phone_number: "0943104326",
        gender: "male",
        type: "teacher",
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    });
}

function login() {
    router.post('/login', {
        emailOrPhone: "test@example.com",
        password: "password"
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function logout() {
    router.post('/logout', {}, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function assignRoles() {
    router.post('/roles/assign', {
        user_id: 2,
        roles: ["manage-roles", "manage-subjects"]
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function removeRoles() {
    router.post('/roles/remove', {
        user_id: 2,
        roles: ["manage-roles", "manage-subjects"]
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function showAllRoles() {
    router.get('/roles', {}, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function userDetails() {
    router.get('/roles/user/details', {
        user_id: 1
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function roleActivities() {
    router.get('/roles/activities', {
        roles: ["manage-roles"],
        user_id: 3
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function addSubject() {
    router.post('/subjects/create', {
        full_name: "Test Subject",
        short_name: "TS",
        category: "test",
        tags: ["test", "test2"]
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function addSubjects() {
    router.post('/subjects/create-bulk', {
        subjects: [
            {
                full_name: "Test Subject 1",
                short_name: "TS1",
                category: "test1",
                tags: ["test", "test2"]

            },
            {
                full_name: "Test Subject 2",
                short_name: "TS2",
                category: "test2",
                tags: ["test", "test5"]
            },
            {
                full_name: "Test Subject 3",
                short_name: "TS3",
                category: "test3",
                tags: ["test4", "test2"]
            },
        ]
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

// Change the id to the id of the subject you want to delete
function deleteSubject() {
    router.delete('/subject/delete/' + 2, {}, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

// To close a school year, set the end_date
function addSchoolYear() {
    router.post('/school-year/create', {
        start_date: "2024-01-01",
        number_of_semesters: 2,
        name: "2023-2024"
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

// Before creating a semester, you must have an active school year
function addSemesters() {
    router.post('/semester/create', {
        semesters: [
            {
                name: "First Semester",
                start_date: "2024-01-01",
                end_date: "2024-06-30"
            },
            {
                name: "Second Semester",
                start_date: "2024-07-01",
                end_date: "2024-12-31"
            }
        ]
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function updateSemester() {
    router.post('/semester/update', {
        id: 1,
        name: "Second Updated Semester",
        start_date: "2024-01-01",
        end_date: "2024-06-30"

    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function listSemesters() {
    router.get('/semester/list', {}, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function deleteSemester() {
    router.delete('/semester/delete/' + 1, {}, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function addBatch() {
    router.post('/batches/create', {
        level_id: 1,
        section: "A",
        min_students: 34,
        max_students: 51
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function addBatches() {
    router.post('/batches/create-bulk', {
            batches: [
                {
                    level_id: 1,
                    no_of_sections: 3,
                    min_students: 10,
                    max_students: 20
                },
                {
                    level_id: 2,
                    no_of_sections: 2
                }
            ]
        },
        {
            onSuccess: () => {
                console.log("Success")
            },
            onError: (error) => {
                console.log("Error")
                console.log(error)
            }
        })
}

// If there is no school year id, it will return all batches
function getBatches() {
    router.get('/batches', {
        school_year_id: 1
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function activeBatches() {
    router.get('/batches/active', {}, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function assignHomeRoomTeacher() {
    router.post('/teachers/assign/homeroom', {
        batch_id: 2,
        teacher_id: 2,
        replace: false
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function removeHomeRoomTeacher() {
    router.delete('/teachers/remove/homeroom/' + 1, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function getHomeRoomTeachers() {
    router.get('/teachers/homerooms', {
        teacher_id: 1
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function addStudentToBatch() {
    router.post('/batches/students/add', {
        batch_id: 10,
        student_id: 5
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function getBatchStudents() {
    router.get('/batches/students', {
        batch_id: 10
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function addBatchSubjects() {
    router.post('/batches/subjects/assign', {
        batches_subjects: [
            {
                batch_id: 10,
                subject_ids: [1, 2]

            },
            {
                batch_id: 11,
                subject_ids: [1, 2]
            }
        ]
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function addUserPosition() {
    router.post('/positions/create', {
        name: "UnitLeader",
        description: "Unit Leader",
        role_names: ["manage-subjects", "manage-teachers"]
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function addLevel() {
    router.post('/levels/create', {
        name: "Grade 13",
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function assignBatchSubjectsTeachers() {
    router.post('/batches/subjects/assign/teacher', {
        batch_subjects_teachers: [
            {
                batch_subject_id: 4,
                teacher_id: 2
            },
            {
                batch_subject_id: 3,
                teacher_id: 3
            }
        ]
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function getLevels() {
    router.get('/levels', {}, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function updateUserPosition() {
    router.post('/positions/update/', {
        name: "Leader",
        description: "Leader",
        role_names: ["manage-subjects", "manage-teachers"]
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function deleteUserPosition() {
    router.delete('/positions/' + 1, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function addSchoolSchedule() {
    router.post('/school-schedules/create', {
        start_date: "2024-01-01",
        end_date: "2024-12-31",
        title: "Easter",
        type: "closed",
        tags: ['Holiday'],
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function updateSchoolSchedule() {
    router.post('/school-schedules/update', {
        start_date: "2024-01-01",
        end_date: "2024-12-31",
        title: "Teachers Parents meeting",
        type: "not_closed",
        tags: ['after school'],
        id: 27
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

//- Filter by school_year, type, start_date, and end_date; if no filter is applied,
// all schedules for the current school year are returned.
function getSchoolSchedules() {
    router.get('/school-schedules', {
        school_year_id: 1,
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function deleteSchoolSchedule() {
    router.delete('/school-schedules/' + 2, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function getAnnouncements() {
    router.get('/announcements', {}, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function createAnnouncement() {
    router.post('/announcements/create', {
        title: 'New announcement',
        body: 'This is a test announcement',
        expires_on: '2020-12-13',
        target_group: ['all', 'students'],

    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function updateAnnouncement() {
    router.post('/announcements/update', {
        id: 1,
        title: "Announcement 2",
        body: "Announcement 2 description",
        expires_on: "2020-12-13",
        target_group: ["teachers"],
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function deleteAnnouncement() {
    router.delete('/announcements/' + 1, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function createSchoolPeriod() {
    router.post('/school-periods/create', {
        school_periods: [
            {
                no_of_periods: 8,
                minutes_per_period: 40,
                start_time: "02:30",
                level_category_ids: [1, 3],
                custom_periods: [
                    {
                        name: "HomeroomPeriod",
                        duration: 10,
                        before_period: 1,
                    },
                    {
                        name: "BreakFast",
                        duration: 20,
                        before_period: 4,
                    },
                    {
                        name: "Lunch",
                        duration: 40,
                        before_period: 6,
                    }
                ]

            },
            {
                no_of_periods: 8,
                minutes_per_period: 40,
                start_time: "02:30",
                level_category_ids: [2],
                custom_periods: [
                    {
                        name: "HomeroomPeriod",
                        duration: 10,
                        before_period: 1
                    },
                    {
                        name: "BreakFast",
                        duration: 20,
                        before_period: 3,
                    },
                    {
                        name: "Lunch",
                        duration: 40,
                        before_period: 5,
                    }
                ]

            },
        ]
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function createBatchSchedule() {
    router.post('/batch-schedules/create', {}, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function checkSchoolPeriod() {
    router.get('/batch-schedules/check', {}, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function addStudentAbsentees() {
    router.post('/absentees/students/add', {
        batch_session_id: 4,
        user_type: "student",
        absentees: [
            {
                user_id: 107,
                reason: "Sick",
            },
            {
                user_id: 109,
                reason: "Sick",
            },
        ],
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

// This function fetches batch sessions with the following filters:
// batch_id, teacher_id, date, and status
function batchSessions() {
    router.get('/sessions/batch', {
        batch_id: 1,
        teacher_id: 25,
        // date: "2023-04-24",
        start_date: "2023-04-24",
        end_date: "2023-04-25",

    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function teacherSessions() {
    router.get('/sessions/teacher', {
        teacher_id: 26,
        status: "scheduled",
        // date: "2023-04-24",
        start_date: "2023-04-24",
        end_date: "2023-04-25",
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function getStudentAbsenteePercentage() {
    router.get('/absentees/student', {
        student_id: 3,
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

function addNotes() {
    router.post('/notes/student/add', {
        student_user_id: 107,
        title: "This is a test note",
        body: "This is a test note body",
        batch_session_id: 1,
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

// This function fetches notes with the following filters:
// student_user_id, batch_session_id, batch_id, school_year_id and author_user_id
function getNotes() {
    router.get('/notes/student', {
        student_user_id: 107,
        school_year_id: 3,
    }, {
        onSuccess: () => {
            console.log("Success")
        },
        onError: (error) => {
            console.log("Error")
            console.log(error)
        }
    })
}

// Make sure the logged-in user is not teacher
function addTeacherFeedback() {
    router.post('/teacher/feedback/add', {
        teacher_id: 26,
        feedback: "This is a test feedback",
    })
}
</script>

<style>
</style>

