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
    }]

export {users};
