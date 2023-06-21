import login from './Amharic/Pages/Auth/login.json'
import signUp from './Amharic/Pages/Auth/signUp.json'
import announcements from './Amharic/Pages/Admin/Announcements/index.json'


// teacher view imports 
import announcementIndex from './Amharic/View/Teacher/Announcement/index.json'
import assessmentsIndex from './Amharic/View/Teacher/Assessments/index.json'
import assessmentsMark from './Amharic/View/Teacher/Assessments/mark.json'

import lessonPlansIndex from './Amharic/View/Teacher/LessonPlans/index.json'
import lessonPlansUpdate from './Amharic/View/Teacher/LessonPlans/update.json'

import AssessmentOutcomePercentages from './Amharic/View/Teacher/Views/Assessments/Details/Views/assessmentOutcomePercentages.json'
const am = {
    "test": {
        "name":"ስም"
    },
    "pages":{
        "auth": {
            login,
            signUp
        },
        "admin":{
            announcements
        },
        "teacher":{

        }
    },
    "views":{
        "teacher":{
            "announcement":{
                announcementIndex
            },
            "assessments":{
                assessmentsIndex,
                assessmentsMark
            },
            "lessonPlans":{
                lessonPlansIndex,
                lessonPlansUpdate
            },
            "views":{
                "assessments":{
                    "details":{
                        "views":{
                            AssessmentOutcomePercentages

                        }
                    }
                }
            }


        }

    }
}

export default am;
