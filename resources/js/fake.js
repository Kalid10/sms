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

export {users, semesters, levels, school_years, statuses};
