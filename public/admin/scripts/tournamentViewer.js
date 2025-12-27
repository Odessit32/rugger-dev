import { newTournamentViewer } from './newTournamentViewer.js'
import { newSeasonViewer } from './newSeasonViewer.js'
import { commonSettingsViewer } from './commonSettingsViewer.js'

export const tournamentViewer = {
    init() {
        this.elem = document.getElementById('tournament-viewer')
        
    },
    elem: undefined,
    show() {
        this.elem.style.display = 'block'
    },
    hide() {
        this.elem.style.display = 'none'
    },
    open(tournamentId) {
        
        newTournamentViewer.hide()
        newSeasonViewer.hide()
        commonSettingsViewer.hide()
        this.show()
    }
}