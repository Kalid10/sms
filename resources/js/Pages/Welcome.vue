<template>
    <div class="">Welcome</div>
    <div class="grid grid-cols-4 gap-6">
        <button class="h-14 bg-lime-500 " @click="registerAdmin">Register Admin</button>
        <button class="h-14 bg-lime-500 " @click="registerGuardian">Register Guardian</button>
        <button class="h-14 bg-lime-500 " @click="registerStudent">Register Student</button>
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
    </div>

</template>
<script setup>
import {router} from "@inertiajs/vue3";

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

function registerGuardian() {
    router.post('/register', {
        name: "Kalid Abdu",
        email: "kalid@gmaill.com",
        gender: "male",
        type: "guardian"
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

function registerStudent() {
    router.post('/register', {
        name: "Kidist Andarge",
        email: "Kidist@gmail.com",
        type: "student",
        gender: "female",
        date_of_birth: "03/07/2000",
        level_id: 1,
        guardian_id: 1,
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
                    no_of_sections: 3
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
        type: "holiday",
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
        title: "Easter",
        type: "holiday",
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
</script>

<style>
</style>

