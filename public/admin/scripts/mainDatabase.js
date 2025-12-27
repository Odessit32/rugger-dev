import { dbPeople } from './dbPeople.js'
import { dbTeams } from './dbTeams.js'
import { dbObjects } from './dbObjects.js'
import { dbSettings } from './dbSettings.js'

dbPeople.init()
dbTeams.init()
dbObjects.init()
dbSettings.init()

dbSettings.print()

$("#databases-viewer").tabs({ active: 0 })
$("#databases-settings-viewer").tabs()
$("#databases-people-profile-viewer").tabs()