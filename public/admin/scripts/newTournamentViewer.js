import { tournamentViewer } from './tournamentViewer.js'
import { newSeasonViewer } from './newSeasonViewer.js'
import { commonSettingsViewer } from './commonSettingsViewer.js'

export const newTournamentViewer = {
    init() {
        this.elem = document.getElementById('new-tournament-viewer')

        let newTournamentButton = document.getElementById('new-tournament')
        newTournamentButton.onclick = function() {
            newTournamentViewer.open()
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
        
        tournamentViewer.hide()
        newSeasonViewer.hide()
        commonSettingsViewer.hide()
        this.show()
    }
}