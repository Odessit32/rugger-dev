export const dbPeople = {
    init() {
        this.element = document.getElementById(
            'db-people-tab'
        )
        this.main.element = document.getElementById(
            'db-people-tab-main'
        )
        this.card.element = document.getElementById(
            'db-people-tab-card'
        )
        this.newTeam.element = document.getElementById(
            'db-people-tab-new-object'
        )
        this.addButton = document.querySelector('#db-people-add-button')
        this.addButton.onclick = function() {
            dbPeople.newTeam.show()
        }

        this.newTeam.backButtonElement = 
            document.getElementById('db-people-new-person-back-button')
        this.newTeam.backButtonElement.onclick = function() {
            dbPeople.main.show()
        }

        this.main.tableElement = 
            document.getElementById('db-people-main-table')

        // this.print()
        // для множественного выбора в select
        /* $("#db-people-tab-new-person-role").mousedown(
            makeMultipleSelect
        ).mousemove(function(e){e.preventDefault()});
        $("#db-people-tab-new-person-position").mousedown(
            makeMultipleSelect
        ).mousemove(function(e){e.preventDefault()});

        function makeMultipleSelect(e) {
            e.preventDefault();
            var select = this;
            var scroll = select .scrollTop;
            e.target.selected = !e.target.selected;
            setTimeout(function(){select.scrollTop = scroll;}, 0);
            $(select ).focus();
        } */

        dbPeople.newTeam.init(this.newTeam.element)
        
    },
    element: undefined,
    addButton: undefined,

    print() {
        // console.log(this)
        this.main.print(dbPeople.main.tableElement)
        // this.card.print()
        this.newTeam.print()
    },
    main: {
        tableElement: undefined,
        async print(tableElement) {
            tableElement.innerHTML = ''

            createTable(tableElement, await getDbObjectsData())

            async function getDbObjectsData() {
                let response = await fetch(
                    '../admin/api.php', {
                        method: 'post',
                        body: JSON.stringify({
                            command: 'getdbPeopleData',
                        })
                    }
                );
                let result = await response.json()
                // console.log(result)
                return result
            }

            function createTable(parent, objectsData) {
                /* console.log(objectsData)
                return 0 */
                let table = document.createElement('div')

                let isFirst = true
                
                for (let row of objectsData) {
                    if (isFirst) {
                        let tableRow = document.createElement('tr')
                    
                        let name = document.createElement('th')
                        name.innerHTML = 'Название'

                        let gender = document.createElement('th')
                        gender.innerHTML = 'Пол'

                        let variation = document.createElement('th')
                        variation.innerHTML = 'Вид'

                        let type = document.createElement('th')
                        type.innerHTML = 'Тип'

                        let tag = document.createElement('th')
                        tag.innerHTML = 'Тэг'

                        tableRow.appendChild(name)
                        tableRow.appendChild(gender)
                        tableRow.appendChild(variation)
                        tableRow.appendChild(type)
                        tableRow.appendChild(tag)
                        table.appendChild(tableRow)

                        isFirst = false
                    }

                    let tableRow = document.createElement('tr')
                    
                    let name = document.createElement('td')
                    name.innerHTML = row.name

                    let gender = document.createElement('td')
                    gender.innerHTML = row.gender

                    let variation = document.createElement('td')
                    variation.innerHTML = row.variation

                    let type = document.createElement('td')
                    type.innerHTML = row.type

                    let tag = document.createElement('td')
                    tag.innerHTML = row.tag

                    tableRow.appendChild(name)
                    tableRow.appendChild(gender)
                    tableRow.appendChild(variation)
                    tableRow.appendChild(type)
                    tableRow.appendChild(tag)
                    tableRow.onclick = tableRowClick

                    table.appendChild(tableRow)

                    function tableRowClick() {
                        dbPeople.card.show()
                        dbPeople.card.print(row.id)
                    }
                }
                parent.appendChild(table)
            }
        },
        clear() {
            this.element.innerHTML = ''
        },
        element: undefined,
        show() {
            dbPeople.card.hide()
            dbPeople.newTeam.hide()
            dbPeople.main.element.style.display = 'block'
        },
        hide() {
            dbPeople.main.element.style.display = 'none'
        }
    },
    card: {
        print(id) {
            this.clear()
            printHeader(this.element)
            printMainPhotoBlock(this.element)
            printTabs(this.element)

            function printHeader(parent) {
                let saveButton = document.createElement('button')
                saveButton.classList.add('btn-danger')
                saveButton.innerHTML = '<- Назад'
                saveButton.setAttribute('type', 'button')
                saveButton.onclick = function() {
                    // dbPeople.main.print()
                    dbPeople.main.show()
                }
                parent.appendChild(saveButton)
            }

            function printMainPhotoBlock(parent) {
                let photoBlock = document.createElement('div')
                photoBlock.classList.add('objects-card-photo-block')
                printMainPhoto(photoBlock)
                printLoadButton(photoBlock)
                parent.appendChild(photoBlock)

                function printMainPhoto(parent) {
                    let mainPhoto = document.createElement('div')
                    mainPhoto.classList.add('objects-card-main-photo')
                    parent.appendChild(mainPhoto)
                }

                function printLoadButton (parent) {
                    let loadButton = document.createElement('button')
                    loadButton.classList.add('btn-danger')
                    loadButton.innerHTML = 'Загрузить'
                    loadButton.setAttribute('type', 'button')
                    loadButton.onclick = function() {
                        console.log('загрузить')
                    }
                    parent.appendChild(loadButton)
                }
            }

            function printTabs(parent) {
                let tabsElement = document.createElement('div')
                tabsElement.id = 'database-teams-card-viewer'

                let ul = document.createElement('ul')

                let li1 = document.createElement('li')
                let a1 = document.createElement('a')
                a1.innerHTML = 'Профиль'
                a1.setAttribute('href', '#database-teams-card-profile')
                li1.appendChild(a1)

                let li2 = document.createElement('li')
                let a2 = document.createElement('a')
                a2.innerHTML = 'Фото'
                a2.setAttribute('href', '#database-teams-card-photo')
                li2.appendChild(a2)

                let li3 = document.createElement('li')
                let a3 = document.createElement('a')
                a3.innerHTML = 'Состав'
                a3.setAttribute('href', '#database-teams-card-members')
                li3.appendChild(a3)

                let li4 = document.createElement('li')
                let a4 = document.createElement('a')
                a4.innerHTML = 'Видео'
                a4.setAttribute('href', '#database-teams-card-video')
                li4.appendChild(a4)

                ul.appendChild(li1)
                ul.appendChild(li3)
                ul.appendChild(li2)
                ul.appendChild(li4)

                tabsElement.appendChild(ul)
                
                let profileTab = document.createElement('div')
                profileTab.id = 'database-teams-card-profile'
                let photoTab = document.createElement('div')
                photoTab.id = 'database-teams-card-photo'
                let membersTab = document.createElement('div')
                membersTab.id = 'database-teams-card-members'
                let videoTab = document.createElement('div')
                videoTab.id = 'database-teams-card-video'

                printProfileTab(profileTab)
                printPhotosTab(photoTab)
                printMembersTab(membersTab)
                printVideoTab(videoTab)

                tabsElement.appendChild(profileTab)
                tabsElement.appendChild(photoTab)
                tabsElement.appendChild(membersTab)
                tabsElement.appendChild(videoTab)

                parent.appendChild(tabsElement)

                $(`#${tabsElement.id}`).tabs()

                async function printMembersTab(parent) {

                }

                async function printVideoTab(parent) {

                }

                async function printProfileTab(parent) {
                    let profileInfo = await getProfileInfo(id)
                    /* console.log(profileInfo)
                    return 0 */
                    
                    let fieldList = {
                        "name": "Название",
                        "short_name": "Короткое название",
                        "city": "Город",
                        "gender": "Пол",
                        "variation": "Вид",
                        "type": "Тип",
                        "tag": "Тэг",
                        "year_of_foundation": "Год основания",
                        "main_trainer": "Главный тренер",
                        "captain": "Капитан",
                        "colors": "Цвета",
                        "link": "Линк",
                        "description": "Описание"
                    }

                    let form = document.createElement('form')

                    for (let field in fieldList) {

                        let fieldElement = document.createElement('div')
                        let fieldCaption = document.createElement('div')
                        fieldCaption.innerHTML = fieldList[field]

                        let value = profileInfo[field]
                        if (value == null) {
                            value = ''
                        }
                        let inputElement = document.createElement('input')
                        inputElement.setAttribute('value', value)

                        if (field == 'description') {
                            inputElement = document.createElement('textarea')
                            inputElement.innerHTML = value
                        }

                        inputElement.setAttribute('data-field', field)

                        fieldElement.appendChild(fieldCaption)
                        fieldElement.appendChild(inputElement)

                        form.appendChild(fieldElement)
                    }
                    
                    parent.appendChild(form)
                    
                    let saveButton = document.createElement('button')
                    saveButton.classList.add('btn-danger')
                    saveButton.innerHTML = 'Сохранить'
                    saveButton.setAttribute('type', 'button')

                    saveButton.onclick = function() {
                        
                        sendFormData(getFormData(parent), id)

                        async function sendFormData(formData, id) {
                            // console.log(formData)
                            // console.log(id)
                            let response = await fetch(
                                '../admin/api.php', {
                                    method: 'post',
                                    body: JSON.stringify({
                                        command: 'saveDbPageTeamCard',
                                        formData,
                                        id
                                    })
                                }
                            );
                            let result = await response.text()
                            // console.log(result)
                            // dbPeople.main.print()
                            dbPeople.main.print(dbPeople.main.tableElement)
                            dbPeople.main.show()
                        }

                        function getFormData(parent) {
                            let formData = {}
                            let formPointsArray = document.querySelectorAll(
                                `#${parent.id} [data-field]`
                            )
                            for (let point of formPointsArray) {
                                let value = point.value
                                if (point.tagName == 'textarea') {
                                    value = point.innerHTML
                                }
                                let field = point.getAttribute('data-field')
                                // console.log(value)
                                formData[field] = value
                            }
                            return formData
                        }
                    }

                    parent.appendChild(saveButton)

                    async function getProfileInfo(id) {

                        let response = await fetch(
                            '../admin/api.php', {
                                method: 'post',
                                body: JSON.stringify({
                                    command: 'dbPageTeamsCardGetProfileInfo',
                                    id
                                })
                            }
                        );
                        let result = await response.json()
                        return result
                        // console.log(result)
                        // dbPeople.main.print()
                        
                    }
                }

                function printPhotosTab(parent) {

                }
            }
        },
        clear() {
            this.element.innerHTML = ''
        },
        element: undefined,
        show() {
            dbPeople.main.hide()
            dbPeople.newTeam.hide()
            dbPeople.card.element.style.display = 'block'
        },
        hide() {
            dbPeople.card.element.style.display = 'none'
        }
    },
    newTeam: {
        init(parent) {
            emptyFieldsToDefault(parent)
            activateFields(parent)

            async function emptyFieldsToDefault(parent) {
                let inputs = document.querySelectorAll(`#${parent.id} input`)
                for (let input of inputs) {
                    input.setAttribute('value', '')
                }
                let rolesElement = document.querySelector(
                    '#db-people-tab-new-person-role'
                )
                rolesElement.innerHTML = ''
                
                // function ()

                let positionElement = document.querySelector(
                    '#db-people-tab-new-person-position'
                )
                let textareas = document.querySelectorAll(
                    `#${parent.id} textarea`
                )
                for (let textarea of textareas) {
                    textarea.innerHTML = ''
                }
            }

            async function activateFields() {
                // do fields is ready for user
                let settings = await getSettings('Игроки')
                // console.log(settings)
                // console.log(element)

                let rolesElement = document.querySelector(
                    '#db-people-tab-new-person-role'
                )
                printRoles(rolesElement)
                // printPositions(positionElement, settings)

                
                /* function printPositions(element, data) {
                    for (let position of data['Игрок']) {
                        
                        let positionElement = document.createElement('option')
                        positionElement.innerHTML = position
                        // positionElement.setAttribute('value', i)
                        
                        element.appendChild(positionElement)
                    }
                } */

                function printRoles(parent) {
                    let commonRolesBlock = createCommonRolesBlock(parent)
                    // addRolesBlock(commonRolesBlock)
                    let newBlockButton = createNewBlockButton(parent)
                    newBlockButton.onclick = function() {
                        addRolesBlock(commonRolesBlock)
                    }
                    
                    function addRolesBlock(parent) {
                        
                        let functionalSelect = createFunctionalSelect(parent)
                        let rolesSelect = createRolesSelect(parent)
                        
                        function createRolesSelect(parent, functionalSelect) {
                            let rolesSelect = document.createElement('select')
                            let i = 0
                            let roles = [
                                'Выберите', 'Игрок', 'Персонал', 
                                'Судья', 'Тренер'
                            ]

                            for (let role of roles) {
    
                                let roleElement = document.createElement(
                                    'option'
                                )
                                roleElement.innerHTML = role
                                roleElement.setAttribute('value', i)
                                rolesSelect.appendChild(roleElement)
                                i++
                            }
                            rolesSelect.onchange = function(event) {
                                rolesSelectOnChange(
                                    parent, functionalSelect, event
                                )
                            }
                            parent.appendChild(rolesSelect)
                            

                            return rolesSelect
                        }

                        function rolesSelectOnChange(
                            parent, functionalSelect, event
                        ) {
                            console.log('changed')
                            console.log(event.target.value)
                        }

                        function createFunctionalSelect(parent) {
                            let functionalSelect = 
                                document.createElement('select')
                            parent.appendChild(functionalSelect)

                            return functionalSelect
                        }
                        
                    }

                    function createCommonRolesBlock(parent) {
                        let commonRolesBlock = document.createElement('div')
                        commonRolesBlock.classList.add('db-people-new-person-common-roles')
                        parent.appendChild(commonRolesBlock)
                        return commonRolesBlock
                    }

                    function createNewBlockButton(parent) {
                        let newBlockButton = document.createElement('div')
                        newBlockButton.innerHTML = '+'
                        parent.appendChild(newBlockButton)
                        return newBlockButton
                    }
                }

                async function getSettings(tab) {
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
                }
            }

        },
        print() {
            // this.clear(this.element)
            
        },
        clear() {
            this.element.innerHTML = ''
        },
        element: undefined,
        show() {
            dbPeople.main.hide()
            dbPeople.card.hide()
            dbPeople.newTeam.element.style.display = 'block'
        },
        hide() {
            dbPeople.newTeam.element.style.display = 'none'
        }
    },
}