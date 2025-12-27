import { newTournamentViewer } from './newTournamentViewer.js'
import { tournamentViewer } from './tournamentViewer.js'
import { commonSettingsViewer } from './commonSettingsViewer.js'

export const newSeasonViewer = {
    init() {
        this.elem = document.getElementById('new-season-viewer')
        
        let newSeasonButton = document.getElementById('new-season-button')
        newSeasonButton.onclick = function() {
            newSeasonViewer.open()
        }
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
        tournamentViewer.hide()
        commonSettingsViewer.hide()
        this.show()
    }
}