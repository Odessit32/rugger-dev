import { newTournamentViewer } from './newTournamentViewer.js'
import { tournamentViewer } from './tournamentViewer.js'
import { newSeasonViewer } from './newSeasonViewer.js'

export const commonSettingsViewer = {
    init() {
        this.elem = document.getElementById('common-settings-viewer')
        
        let newSeasonButton = document.getElementById('common-settings-button')
        newSeasonButton.onclick = function() {
            commonSettingsViewer.open()
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
        newSeasonViewer.hide()
        this.show()
    }
}