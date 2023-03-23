<template>
    <div class="">Welcome</div>
    <button class="bg-red-400 p-1" @click="registerAdmin">Register Admin</button>
    <button class="bg-red-400 p-1" @click="registerGuardian">Register Guardian</button>
    <button class="bg-red-400 p-1" @click="registerStudent">Register Student</button>
    <button class="bg-red-400 p-1" @click="registerTeacher">Register Teacher</button>
    <button class="bg-red-400 p-1" @click="login">Test Login</button>
    <button class="bg-red-400 p-1" @click="logout">Test Logout</button>
    <button class="bg-red-400 p-1" @click="assignRoles">Test Assign Role</button>
    <button class="bg-red-400 p-1" @click="removeRoles">Test Remove Role</button>
    <button class="bg-red-400 p-1" @click="showAllRoles">Get Roles</button>
    <button class="bg-red-400 p-1" @click="userDetails">User Details</button>
    <button class="bg-red-400 p-1" @click="roleActivities">Test Role Activities</button>
    <button class="bg-red-400 p-1" @click="addSubject">Test Add Subject</button>
    <button class="bg-red-400 p-1" @click="deleteSubject">Test Delete Subject</button>
    <button class="bg-red-400 p-1" @click="addSchoolYear">Test Add School Year</button>
    <button class="bg-red-400 p-1" @click="addSemesters">Test Create Semester</button>
    <button class="bg-red-400 p-1" @click="updateSemester">Test Update Semester</button>
    <button class="bg-red-400 p-1" @click="listSemesters">Test List Semester</button>
    <button class="bg-red-400 p-1" @click="deleteSemester">Test Delete Semester</button>
    <button class="bg-red-400 p-1" @click="addBatch">Test Add Batch</button>
    <button class="bg-red-400 p-1" @click="addBatches">Test Add Batches</button>
    <button class="bg-red-400 p-1" @click="getBatches">Test Get Batches</button>
    <button class="bg-red-400 p-1" @click="activeBatches">Test Get Active Batches</button>
    <button class="bg-red-400 p-1" @click="assignHomeRoomTeacher">Test Assign Homeroom Teacher</button>
    <button class="bg-red-400 p-1" @click="removeHomeRoomTeacher">Test Remove Homeroom Teacher</button>

</template>
<script setup>
import {router} from "@inertiajs/vue3";

// Register admin
function registerAdmin() {
    router.post('/register', {
        name: "Biniyam Lemma",
        email: "admin@gmaill.com",
        position: "Principal",
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
    router.post('/subject/create', {
        full_name: "Test Subject",
        short_name: "TS",
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
        end_date: null
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

function addBatches() {
    router.post('/batches/create_bulk', {
            batches: {
                school_year_id: 1,
                grade: [
                    {
                        level_id: 1,
                        sections: ["A", "B"]
                    },
                    {
                        level_id: 2,
                        sections: ["A", "B"]
                    }
                ]
            }
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
</script>

<style>
</style>

