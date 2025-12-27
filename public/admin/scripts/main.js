import { championshipList } from './championshipList.js'
import { tournamentViewer } from './tournamentViewer.js'
import { newTournamentViewer } from './newTournamentViewer.js'
import { newSeasonViewer } from './newSeasonViewer.js'
import { commonSettingsViewer } from './commonSettingsViewer.js'

championshipList.init()
tournamentViewer.init()
newTournamentViewer.init()
newSeasonViewer.init()
commonSettingsViewer.init()

$( "#tournament-viewer" ).tabs()