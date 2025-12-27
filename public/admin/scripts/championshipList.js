import { tournamentViewer } from './tournamentViewer.js'
import { newTournamentViewer } from './newTournamentViewer.js'

export const championshipList = {
    currentTournament: undefined,
    async init() {
        this.newTournament.init()
        this.print(await this.get())
    },
    newTournament: {
        init() {
            this.elem = document.getElementById('new-tournament')
            this.elem.onclick = this.click
        },
        elem: undefined,
        click() {

        }
    },
    async get() {
        let response = await fetch(
            '../admin/api.php',
            {
                method: 'post',
                body: JSON.stringify({
                    command: 'getChampionshipList'
                })
            }
        )
        let list = await response.json()
        return list
    },
    print(list) {
        for (let tournament of list) {
            const name = document.createElement('div')
            name.innerText = tournament.chg_title_ru
            name.classList.add('name')

            const season = document.createElement('div')
            season.innerText = tournament.ch_title_ru
            season.classList.add('season')

            const tournamentElem = document.createElement('div')
            tournamentElem.classList.add('tournament')
            tournamentElem.appendChild(name)
            tournamentElem.appendChild(season)
            tournamentElem.onclick = (event) => {
                this.tournamentClick(event, tournament.ch_id)
            }

            const tournamentList = document.querySelector('#tournament-list')
            tournamentList.appendChild(tournamentElem)
        }
    },
    tournamentClick(event, tournamentId) {
        if (event.target == this.currentTournament) {
            return
        }
        this.markLikeActive(event.target, event)
        tournamentViewer.open(tournamentId)
    },
    markLikeActive(tournament, event) {
        if (this.currentTournament != undefined) {
            this.currentTournament.classList.remove('active')
        }
        tournament.classList.add('active')
        this.currentTournament = event.target
    }
}