import {ref} from "vue";

const users = [{
    "id": 1,
    "name": "Bartram Lockhurst",
    "email": "blockhurst0@free.fr",
    "phone": "616-968-0286",
    "type": "teacher",
    "roles": "manage-users",
    "active": false
},
    {
        "id": 2,
        "name": "Ramsay Chichgar",
        "email": "rchichgar1@dedecms.com",
        "phone": "669-911-1888",
        "type": "admin",
        "roles": "manage-all",
        "active": true
    },
    {
        "id": 3,
        "name": "Kev Sign",
        "email": "ksign2@paginegialle.it",
        "phone": "423-981-2550",
        "type": "admin",
        "roles": "manage-roles",
        "active": false
    },
    {
        "id": 4,
        "name": "Franzen Blesing",
        "email": "fblesing3@slashdot.org",
        "phone": "105-181-1832",
        "type": "teacher",
        "roles": "manage-all",
        "active": false
    },
    {
        "id": 5,
        "name": "Tybalt Casford",
        "email": "tcasford4@vkontakte.ru",
        "phone": "150-973-6726",
        "type": "teacher",
        "roles": "manage-all",
        "active": true
    },
    {
        "id": 6,
        "name": "Bartie Urridge",
        "email": "burridge5@artisteer.com",
        "phone": "667-629-7684",
        "type": "teacher",
        "roles": "manage-teachers",
        "active": true
    },
    {
        "id": 7,
        "name": "Rozella Stook",
        "email": "rstook6@quantcast.com",
        "phone": "223-685-4070",
        "type": "admin",
        "roles": "manage-all",
        "active": false
    },
    {
        "id": 8,
        "name": "Alvinia Sheffield",
        "email": "asheffield7@webmd.com",
        "phone": "726-248-2226",
        "type": "admin",
        "roles": "manage-assessment",
        "active": true
    },
    {
        "id": 9,
        "name": "Sheffie Breydin",
        "email": "sbreydin8@nifty.com",
        "phone": "216-786-5427",
        "type": "admin",
        "roles": "manage-assessment",
        "active": false
    },
    {
        "id": 10,
        "name": "Terra Bull",
        "email": "tbull9@trellian.com",
        "phone": "431-228-6506",
        "type": "admin",
        "roles": "manage-assessment",
        "active": true
    },
    {
        "id": 11,
        "name": "Reider McLarty",
        "email": "rmclartya@ft.com",
        "phone": "294-649-6797",
        "type": "admin",
        "roles": "manage-assignments",
        "active": false
    },
    {
        "id": 12,
        "name": "Olivier Dumbellow",
        "email": "odumbellowb@wp.com",
        "phone": "486-526-1583",
        "type": "admin",
        "roles": "manage-users",
        "active": true
    },
    {
        "id": 13,
        "name": "Lem Bedburrow",
        "email": "lbedburrowc@topsy.com",
        "phone": "428-796-2004",
        "type": "teacher",
        "roles": "manage-roles",
        "active": true
    },
    {
        "id": 14,
        "name": "Dinnie Malby",
        "email": "dmalbyd@seattletimes.com",
        "phone": "113-357-9998",
        "type": "teacher",
        "roles": "manage-assignments",
        "active": true
    },
    {
        "id": 15,
        "name": "Fina McLanachan",
        "email": "fmclanachane@blogs.com",
        "phone": "880-111-9990",
        "type": "teacher",
        "roles": "manage-assessment",
        "active": true
    },
    {
        "id": 16,
        "name": "Terrie Masson",
        "email": "tmassonf@twitter.com",
        "phone": "826-564-4167",
        "type": "admin",
        "roles": "manage-all",
        "active": false
    },
    {
        "id": 17,
        "name": "Lucias Rubenov",
        "email": "lrubenovg@house.gov",
        "phone": "470-958-6163",
        "type": "admin",
        "roles": "manage-assignments",
        "active": true
    },
    {
        "id": 18,
        "name": "Edgar Peerless",
        "email": "epeerlessh@virginia.edu",
        "phone": "767-875-3524",
        "type": "admin",
        "roles": "manage-assessment",
        "active": true
    },
    {
        "id": 19,
        "name": "Amalee Baty",
        "email": "abatyi@cornell.edu",
        "phone": "404-214-9108",
        "type": "teacher",
        "roles": "manage-users",
        "active": true
    },
    {
        "id": 20,
        "name": "Corene Leahey",
        "email": "cleaheyj@npr.org",
        "phone": "266-619-4618",
        "type": "teacher",
        "roles": "manage-teachers",
        "active": true
    },
    {
        "id": 21,
        "name": "Horatio Vouls",
        "email": "hvoulsk@wp.com",
        "phone": "912-414-1666",
        "type": "admin",
        "roles": "manage-assessment",
        "active": false
    },
    {
        "id": 22,
        "name": "Elane Seeler",
        "email": "eseelerl@nhs.uk",
        "phone": "374-149-1154",
        "type": "teacher",
        "roles": "manage-users",
        "active": false
    },
    {
        "id": 23,
        "name": "Morna Normavill",
        "email": "mnormavillm@skyrock.com",
        "phone": "587-520-1915",
        "type": "admin",
        "roles": "manage-users",
        "active": true
    },
    {
        "id": 24,
        "name": "Guillaume Stimpson",
        "email": "gstimpsonn@t-online.de",
        "phone": "406-921-9489",
        "type": "admin",
        "roles": "manage-teachers",
        "active": true
    },
    {
        "id": 25,
        "name": "Archie Giacomuzzo",
        "email": "agiacomuzzoo@toplist.cz",
        "phone": "876-557-0501",
        "type": "admin",
        "roles": "manage-assignments",
        "active": true
    },
    {
        "id": 26,
        "name": "Nina Hogsden",
        "email": "nhogsdenp@shareasale.com",
        "phone": "229-631-9137",
        "type": "admin",
        "roles": "manage-assessment",
        "active": true
    },
    {
        "id": 27,
        "name": "Cassondra Newiss",
        "email": "cnewissq@wikispaces.com",
        "phone": "814-741-2756",
        "type": "teacher",
        "roles": "manage-users",
        "active": false
    },
    {
        "id": 28,
        "name": "Zorana Giovanetti",
        "email": "zgiovanettir@godaddy.com",
        "phone": "377-681-9125",
        "type": "admin",
        "roles": "manage-all",
        "active": true
    },
    {
        "id": 29,
        "name": "Weidar Genner",
        "email": "wgenners@smugmug.com",
        "phone": "526-184-1264",
        "type": "admin",
        "roles": "manage-assessment",
        "active": true
    },
    {
        "id": 30,
        "name": "Odilia Stirgess",
        "email": "ostirgesst@cargocollective.com",
        "phone": "664-602-0904",
        "type": "admin",
        "roles": "manage-teachers",
        "active": true
    }
]

// create a list of semester objects with attributes id, name, start_date, end_date, school_year, status, last_updated
const semesters = [
    {
        "id": 1,
        "name": "Fall 2019",
        "start_date": "2019-08-26",
        "end_date": "2019-12-13",
        "school_year": "2019/2020",
        "status": "completed",
        "last_updated": "2019-12-31",
    },
    {
        "id": 2,
        "name": "Spring 2020",
        "start_date": "2020-01-06",
        "end_date": "2020-05-15",
        "school_year": "2019/2020",
        "status": "completed",
        "last_updated": "2020-05-31",
    },
    {
        "id": 3,
        "name": "Summer 2020",
        "start_date": "2020-05-18",
        "end_date": "2020-08-21",
        "school_year": "2019/2020",
        "status": "completed",
        "last_updated": "2020-08-31",
    },
    {
        "id": 4,
        "name": "Fall 2020",
        "start_date": "2020-08-24",
        "end_date": "2020-12-11",
        "school_year": "2020/2021",
        "status": "completed",
        "last_updated": "2020-12-31",
    },
    {
        "id": 5,
        "name": "Spring 2021",
        "start_date": "2021-01-04",
        "end_date": "2021-05-21",
        "school_year": "2020/2021",
        "status": "completed",
        "last_updated": "2021-05-31",
    },
    {
        "id": 6,
        "name": "Summer 2021",
        "start_date": "2021-05-24",
        "end_date": "2021-08-20",
        "school_year": "2020/2021",
        "status": "completed",
        "last_updated": "2021-08-31",
    },
    {
        "id": 7,
        "name": "Fall 2021",
        "start_date": "2021-08-23",
        "end_date": "2021-12-10",
        "school_year": "2021/2022",
        "status": "completed",
        "last_updated": "2021-12-31",
    },
    {
        "id": 8,
        "name": "Spring 2022",
        "start_date": "2022-01-03",
        "end_date": "2022-05-20",
        "school_year": "2021/2022",
        "status": "completed",
        "last_updated": "2022-05-31",
    },
    {
        "id": 9,
        "name": "Summer 2022",
        "start_date": "2022-05-23",
        "end_date": "2022-08-19",
        "school_year": "2021/2022",
        "status": "completed",
        "last_updated": "2022-08-31",
    },
    {
        "id": 10,
        "name": "Fall 2022",
        "start_date": "2022-08-22",
        "end_date": "2022-12-09",
        "school_year": "2022/2023",
        "status": "completed",
        "last_updated": "2022-12-31",
    },
    {
        "id": 11,
        "name": "Spring 2023",
        "start_date": "2023-01-02",
        "school_year": "2022/2023",
        "status": "active",
        "last_updated": "2023-01-31",
    },
    {
        "id": 12,
        "name": "Summer 2023",
        "start_date": "2023-05-22",
        "school_year": "2022/2023",
        "status": "upcoming",
        "last_updated": "2023-01-31",
    }
]

const school_years = [
    {
        value: 0,
        label: 'All years'
    },
    {
        value: 1,
        label: '2022/2023'
    },
    {
        value: 2,
        label: '2021/2022'
    },
    {
        value: 3,
        label: '2020/2021'
    }
]

const statuses = [
    {
        value: 'upcoming',
        label: 'Upcoming'
    },
    {
        value: 'active',
        label: 'Active'
    },
    {
        value: 'completed',
        label: 'Completed'
    }
]

// create a list of level objects with attributes id, name, sections, last_updated
const levels = [
    {
        id: 1,
        name: 'Level 1',
        statistics: {
            students: 78,
            teachers: 4,
            sections: ['A', 'B', 'C', 'D'],
            subjects: 6
        },
        last_updated: '2020-02-22'
    },
    {
        id: 2,
        name: 'Level 2',
        statistics: {
            students: 71,
            teachers: 7,
            sections: ['A', 'B', 'C'],
            subjects: 9
        },
        last_updated: '2021-03-31'
    },
    {
        id: 3,
        name: 'Level 3',
        statistics: {
            students: 64,
            teachers: 8,
            sections: ['A', 'B', 'C'],
            subjects: 7
        },
        last_updated: '2020-03-31'
    },
    {
        id: 4,
        name: 'Level 4',
        statistics: {
            students: 69,
            teachers: 9,
            sections: ['A', 'B', 'C'],
            subjects: 9
        },
        last_updated: '2019-01-31'
    },
    {
        id: 5,
        name: 'Level 5',
        statistics: {
            students: 75,
            teachers: 9,
            sections: ['A', 'B', 'C', 'D'],
            subjects: 9
        },
        last_updated: '2020-01-28'
    },
    {
        id: 6,
        name: 'Level 6',
        statistics: {
            students: 72,
            teachers: 12,
            sections: ['A', 'B', 'C', 'D'],
            subjects: 9
        },
        last_updated: '2019-11-29'
    },
    {
        id: 7,
        name: 'Level 7',
        statistics: {
            students: 65,
            teachers: 13,
            sections: ['A', 'B', 'C', 'D'],
            subjects: 11
        },
        last_updated: '2020-02-22'
    },
    {
        id: 8,
        name: 'Level 8',
        statistics: {
            students: 62,
            teachers: 11,
            sections: ['A', 'B', 'C', 'D'],
            subjects: 11
        },
        last_updated: '2021-03-31'
    },
    {
        id: 9,
        name: 'Level 9',
        statistics: {
            students: 69,
            teachers: 11,
            sections: ['A', 'B', 'C'],
            subjects: 10
        },
        last_updated: '2020-03-31'
    },
    {
        id: 10,
        name: 'Level 10',
        statistics: {
            students: 69,
            teachers: 10,
            sections: ['A', 'B', 'C'],
            subjects: 10
        },
        last_updated: '2019-01-31'
    },
    {
        id: 11,
        name: 'Level 11',
        statistics: {
            students: 64,
            teachers: 14,
            sections: ['Natural A', 'Natural B', 'Social A', 'Social B'],
            subjects: 12
        },
        last_updated: '2020-01-28'
    },
    {
        id: 12,
        name: 'Level 12',
        statistics: {
            students: 61,
            teachers: 13,
            sections: ['Natural A', 'Natural B', 'Social A', 'Social B'],
            subjects: 12
        },
        last_updated: '2019-11-29'
    },
]
const batches = [
    { id: 1, level: 1, section: 'A' },
    { id: 2, level: 1, section: 'B' },
    { id: 3, level: 1, section: 'C' },
    { id: 4, level: 1, section: 'D' },
    { id: 5, level: 2, section: 'A' },
    { id: 6, level: 2, section: 'B' },
    { id: 9, level: 3, section: 'A' },
    { id: 10, level: 3, section: 'B' },
    { id: 11, level: 3, section: 'C' },
    { id: 12, level: 3, section: 'D' },
    { id: 13, level: 4, section: 'A' },
    { id: 14, level: 4, section: 'B' },
    { id: 15, level: 4, section: 'C' },
    { id: 16, level: 4, section: 'D' },
    { id: 17, level: 5, section: 'A' },
    { id: 18, level: 5, section: 'B' },
    { id: 19, level: 5, section: 'C' },
    { id: 20, level: 5, section: 'D' },
    { id: 21, level: 6, section: 'A' },
    { id: 22, level: 6, section: 'B' },
    { id: 23, level: 6, section: 'C' },
    { id: 24, level: 6, section: 'D' },
    { id: 25, level: 7, section: 'A' },
    { id: 26, level: 7, section: 'B' },
    { id: 27, level: 7, section: 'C' },
    { id: 28, level: 7, section: 'D' },
    { id: 29, level: 8, section: 'A' },
    { id: 30, level: 8, section: 'B' },
    { id: 31, level: 8, section: 'C' },
    { id: 32, level: 8, section: 'D' },
    { id: 33, level: 9, section: 'A' },
    { id: 34, level: 9, section: 'B' },
    { id: 35, level: 9, section: 'C' },
    { id: 36, level: 9, section: 'D' },
    { id: 37, level: 10, section: 'A' },
    { id: 38, level: 10, section: 'B' },
    { id: 39, level: 10, section: 'C' },
    { id: 40, level: 10, section: 'D' },
    { id: 41, level: 11, section: 'A' },
    { id: 42, level: 11, section: 'B' },
    { id: 43, level: 11, section: 'C' },
    { id: 44, level: 11, section: 'D' },
    { id: 45, level: 12, section: 'A' },
    { id: 46, level: 12, section: 'B' },
    { id: 47, level: 12, section: 'C' },
    { id: 48, level: 12, section: 'D' },
]

const sbj = [
    {id:1,name:'bio'},
    {id:1,name:'bio'},
    {id:1,name:'bio'},
]

const subjects = [
    { id: 1, name: 'Mathematics', short_name: 'Math',  category: 'Mathematics', labels: ['Natural Sciences', 'Social Sciences'] },
    { id: 2, name: 'English', short_name: 'Eng',  category: 'Languages', labels: ['Natural Sciences', 'Social Sciences'] },
    { id: 3, name: 'አማርኛ', short_name: 'አማ',  category: 'Languages', labels: ['Natural Sciences', 'Social Sciences'] },
    { id: 4, name: 'Biology', short_name: 'Bio',  category: 'Natural Science', labels: ['Natural Sciences', 'Middle School', 'High School'] },
    { id: 5, name: 'Chemistry', short_name: 'Chem',  category: 'Natural Science', labels: ['Natural Sciences', 'Middle School', 'High School'] },
    { id: 6, name: 'Physics', short_name: 'Phy',  category: 'Natural Science', labels: ['Natural Sciences', 'Middle School', 'High School'] },
    { id: 7, name: 'Geography', short_name: 'Geo',  category: 'Social Studies', labels: ['Social Sciences', 'Middle School', 'High School'] },
    { id: 8, name: 'History', short_name: 'His',  category: 'Social Studies', labels: ['Social Sciences', 'Middle School', 'High School'] },
    { id: 9, name: 'Arts and Crafts', short_name: 'A&C',  category: 'Extra Curricular', labels: ['Primary School', 'Arts'] },
    { id: 9, name: 'Afaan Orommo', short_name: 'Oro',  category: 'Languages', labels: ['Natural Sciences', 'Social Sciences', 'Extra Curricular'] },
    { id: 10, name: 'Physical Education', short_name: 'PE',  category: 'Extra Curricular', labels: ['Extra Curricular'] },
    { id: 11, name: 'Technical Drawing', short_name: 'TD',  category: 'Preparatory', labels: ['Natural Sciences', 'Preparatory Level'] },
    { id: 12, name: 'Economics', short_name: 'Econ',  category: 'Preparatory', labels: ['Social Sciences', 'Preparatory Level'] },
    { id: 13, name: 'Business', short_name: 'Bus',  category: 'Preparatory', labels: ['Social Sciences', 'Preparatory Level'] },
]

function allGrades(batches) {
    return batches.reduce((acc, batch) => {
        if (!acc.includes(batch.level)) {
            acc.push(batch.level)
        }
        return acc
    }, [])
}
function sectionsOfLevel(batches, level) {
    return batches.filter(batch => batch.level === level).map(batch => batch.section)
}


export {users, semesters, levels, school_years, statuses,sbj,batches,subjects,allGrades,sectionsOfLevel};
