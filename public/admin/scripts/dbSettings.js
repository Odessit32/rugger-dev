export const dbSettings = {
    init() {
        this.peopleTab.element = document.getElementById(
            'db-settings-people-tab'
        )
        this.teamsTab.element = document.getElementById(
            'db-settings-teams-tab'
        )
        /* this.objectsTab.element = document.getElementById(
            'db-settings-objects-tab'
        ) */
    },
    print() {
        // console.log(this)
        this.peopleTab.print()
        this.teamsTab.print()
        // this.objectsTab.print()
    },
    async sendNewSettingsData(tab, newSettingsData) {
        let response = await fetch(
            '../admin/api.php', {
                method: 'post',
                body: JSON.stringify({
                    command: 'saveDbPageSettings',
                    tab,
                    newSettingsData
                })
            }
        );
        let result = await response.text()
        console.log(result)
    },
    printTab(params) {
        // element - dom-элемент нужной вкладки (для вставки контента)
        // settingsData - список настроек с базы данных
        // arrayOfCols - список колонок данной вкладки настроек с нужным
        //      порядком
        // tab - вкладка
        // tabEnglish - вкладка на английском

        let captureElement = document.createElement('div')
        captureElement.classList.add('db-settings-capture')
        
        for (let col of params.arrayOfCols) {

            let capturePoint = document.createElement('div')
            capturePoint.classList.add('db-settings-capture-point')
            capturePoint.innerHTML = col

            captureElement.appendChild(capturePoint)
        }

        params.element.appendChild(captureElement)

        for (let col of params.arrayOfCols) {

            let colElement = document.createElement('div')
            colElement.setAttribute('data-colname', col)
            colElement.classList.add('db-settings-col')

            for (let point of params.settingsData[col]) {
                colElement.appendChild(getNewPoint(point))
            }
            
            params.element.appendChild(colElement)
        }

        for (let col of params.arrayOfCols) {
            
            let colElement = document.querySelector(`div[data-colname=${col}]`)
            let plusButton = document.createElement('div')
            colElement.appendChild(plusButton)

            
            plusButton.innerHTML = '+'
            plusButton.classList.add('db-settings-new-point-button')
            plusButton.onclick = function() {
                colElement.insertBefore(getNewPoint(), plusButton)
            }

        }

        let saveBlock = document.createElement('div')
        saveBlock.classList.add('db-settings-save-block')
        saveBlock.appendChild(getSaveButton())
        params.element.appendChild(saveBlock)

        function getSaveButton() {
            let saveButton = document.createElement('button')
            saveButton.classList.add('btn-danger')
            saveButton.innerHTML = 'Сохранить'
            saveButton.setAttribute('type', 'button')

            saveButton.onclick = function() {
                let newSettingsData = {}

                for (let col of params.arrayOfCols) {

                    newSettingsData[col] = []
                    let inputArray = document.querySelectorAll(`
                        #${params.element.id}
                        [data-colname=${col}]
                        [type=text]
                    `)
                    for (let input of inputArray) {
                        // console.log(input.value)
                        newSettingsData[col].push(input.value)
                    }
                }

                // console.log(newSettingsData)
                dbSettings.sendNewSettingsData(params.tab, newSettingsData)
                dbSettings[params.tabEnglish].print()
            }

            return saveButton
        }

        function getNewPoint(point = '') {
            let pointElement = document.createElement('div')
            pointElement.classList.add('db-settings-point')

            let pointInput = document.createElement('input')
            pointInput.classList.add('db-settings-input')
            pointInput.setAttribute('value', point)
            pointInput.setAttribute('type', 'text')

            let pointRemoveButton = document.createElement('div')
            pointRemoveButton.classList.add('db-settings-remove-button')
            pointRemoveButton.innerHTML = 'X'
            pointRemoveButton.onclick = function() {
                pointElement.parentNode.removeChild(pointElement)
            }

            pointElement.appendChild(pointInput)
            pointElement.appendChild(pointRemoveButton)

            return pointElement
        }
    },
    settingsSelection: {
        'Игроки': ['Игрок', 'Тренер', 'Судья', 'Персонал'],
        'Команды': ['Пол', 'Вид', 'Тип', 'Тэг'],
        'Объекты': ['Стадионы']
    },
    peopleTab: {
        async print() {
            this.clear()
            let tab = 'Игроки'
            let tabEnglish = 'peopleTab'
            let settingsData = await dbSettings.get(tab)
            let arrayOfCols = dbSettings.settingsSelection[tab]
            dbSettings.printTab({
                element: this.element,
                settingsData,
                arrayOfCols,
                tab,
                tabEnglish
            })
        },
        clear() {
            this.element.innerHTML = ''
        },
        element: undefined
    },
    teamsTab: {
        async print() {
            this.clear()
            let tab = 'Команды'
            let tabEnglish = 'teamsTab'
            let settingsData = await dbSettings.get(tab)
            let arrayOfCols = dbSettings.settingsSelection[tab]
            dbSettings.printTab({
                element: this.element,
                settingsData,
                arrayOfCols,
                tab,
                tabEnglish
            })
        },
        clear() {
            this.element.innerHTML = ''
        },
        element: undefined
    },
    objectsTab: {
        async print() {
            this.clear()
            let tab = 'Объекты'
            let tabEnglish = 'objectsTab'
            let settingsData = await dbSettings.get(tab)
            let arrayOfCols = dbSettings.settingsSelection[tab]
            dbSettings.printTab({
                element: this.element,
                settingsData,
                arrayOfCols,
                tab,
                tabEnglish
            })
        },
        clear() {
            this.element.innerHTML = ''
        },
        element: undefined
    },
    async get(tab) {
        let response = await fetch(
            '../admin/api.php', {
                method: 'post',
                body: JSON.stringify({
                    command: 'getDbPageSettings',
                    tab
                })
            }
        );
        let list = await response.json()
        return list
    },
}