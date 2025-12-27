export const dbObjects = {
    init() {
        this.element = document.getElementById(
            'db-objects-tab'
        )
        this.main.element = document.getElementById(
            'db-objects-tab-main'
        )
        this.card.element = document.getElementById(
            'db-objects-tab-card'
        )
        this.newObject.element = document.getElementById(
            'db-objects-tab-new-object'
        )
        this.print()
    },
    element: undefined,
    print() {
        // console.log(this)
        this.main.print()
        // this.card.print()
        this.newObject.print()
    },
    main: {
        async print() {
            this.clear()
            createHeader(this.element)
            createTable(this.element, await getDbObjectsData())

            function createHeader(parent) {
                let header = document.createElement('div')

                let caption = document.createElement('div')
                caption.innerHTML = 'Список стадионов'

                let buttons = document.createElement('div')

                let input = document.createElement('input')
                input.setAttribute('type', 'text')
                input.setAttribute('placeholder', 'поиск')

                let addButton = document.createElement('button')
                addButton.classList.add('btn-danger')
                addButton.innerHTML = 'Добавить'
                addButton.setAttribute('type', 'button')
                addButton.onclick = function() {
                    dbObjects.newObject.show()
                }

                buttons.appendChild(input)
                buttons.appendChild(addButton)

                header.appendChild(caption)
                header.appendChild(buttons)

                parent.appendChild(header)
            }

            async function getDbObjectsData() {
                let response = await fetch(
                    '../admin/api.php', {
                        method: 'post',
                        body: JSON.stringify({
                            command: 'getDbObjectsData',
                        })
                    }
                );
                let result = await response.json()
                // console.log(result)
                return result
            }

            function createTable(parent, objectsData) {
                let table = document.createElement('div')

                let isFirst = true
                
                for (let row of objectsData) {
                    if (isFirst) {
                        let tableRow = document.createElement('tr')
                    
                        let name = document.createElement('th')
                        name.innerHTML = 'Стадион'

                        let city = document.createElement('th')
                        city.innerHTML = 'Город'

                        tableRow.appendChild(name)
                        tableRow.appendChild(city)
                        table.appendChild(tableRow)

                        isFirst = false
                    }
                    let tableRow = document.createElement('tr')
                    
                    let name = document.createElement('td')
                    name.innerHTML = row.name

                    let city = document.createElement('td')
                    city.innerHTML = row.city

                    tableRow.appendChild(name)
                    tableRow.appendChild(city)
                    tableRow.onclick = tableRowClick

                    table.appendChild(tableRow)

                    function tableRowClick() {
                        dbObjects.card.show()
                        dbObjects.card.print(row.id)
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
            dbObjects.card.hide()
            dbObjects.newObject.hide()
            dbObjects.main.element.style.display = 'block'
        },
        hide() {
            dbObjects.main.element.style.display = 'none'
        }
    },
    card: {
        print(id) {
            this.clear()
            printHeader(this.element)
            printMainPhotoBlock(this.element)
            printTabs(this.element)

            function printHeader(parent) {
                let backButton = document.createElement('button')
                backButton.classList.add('btn-danger')
                backButton.innerHTML = '<- Назад'
                backButton.setAttribute('type', 'button')
                backButton.onclick = function() {
                    dbObjects.main.print()
                    dbObjects.main.show()
                }
                parent.appendChild(backButton)
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
                tabsElement.id = 'database-object-card-viewer'

                let ul = document.createElement('ul')

                let li1 = document.createElement('li')
                let a1 = document.createElement('a')
                a1.innerHTML = 'Профиль'
                a1.setAttribute('href', '#database-object-card-profile')
                li1.appendChild(a1)

                let li2 = document.createElement('li')
                let a2 = document.createElement('a')
                a2.innerHTML = 'Фото'
                a2.setAttribute('href', '#database-object-card-photo')
                li2.appendChild(a2)

                ul.appendChild(li1)
                ul.appendChild(li2)

                tabsElement.appendChild(ul)
                
                let profileTab = document.createElement('div')
                profileTab.id = 'database-object-card-profile'
                let photoTab = document.createElement('div')
                photoTab.id = 'database-object-card-photo'

                printProfileTab(profileTab)
                printPhotosTab(photoTab)

                tabsElement.appendChild(profileTab)
                tabsElement.appendChild(photoTab)

                parent.appendChild(tabsElement)

                $(`#${tabsElement.id}`).tabs()

                async function printProfileTab(parent) {
                    let profileInfo = await getProfileInfo(id)
                    
                    let form = document.createElement('form')

                    let name = document.createElement('div')
                    let nameCaption = document.createElement('div')
                    nameCaption.innerHTML = 'имя'
                    let nameInput = document.createElement('input')
                    nameInput.setAttribute('data-field', 'name')
                    nameInput.setAttribute('value', profileInfo.name)
                    name.appendChild(nameCaption)
                    name.appendChild(nameInput)

                    let city = document.createElement('div')
                    let cityCaption = document.createElement('div')
                    cityCaption.innerHTML = 'город'
                    let cityInput = document.createElement('input')
                    cityInput.setAttribute('data-field', 'city')
                    cityInput.setAttribute('value', profileInfo.city)
                    city.appendChild(cityCaption)
                    city.appendChild(cityInput)
                    
                    let adress = document.createElement('div')
                    let adressCaption = document.createElement('div')
                    adressCaption.innerHTML = 'адрес'
                    let adressInput = document.createElement('input')
                    adressInput.setAttribute('data-field', 'adress')
                    adressInput.setAttribute('value', profileInfo.adress)
                    adress.appendChild(adressCaption)
                    adress.appendChild(adressInput)

                    let map = document.createElement('div')
                    let mapCaption = document.createElement('div')
                    mapCaption.innerHTML = 'карта'
                    let mapInput = document.createElement('input')
                    mapInput.setAttribute('value', profileInfo.map)
                    mapInput.setAttribute('data-field', 'map')
                    map.appendChild(mapCaption)
                    map.appendChild(mapInput)

                    let link = document.createElement('div')
                    let linkCaption = document.createElement('div')
                    linkCaption.innerHTML = 'link'
                    let linkInput = document.createElement('input')
                    linkInput.setAttribute('value', profileInfo.link)
                    linkInput.setAttribute('data-field', 'link')
                    link.appendChild(linkCaption)
                    link.appendChild(linkInput)

                    let description = document.createElement('div')
                    let descriptionCaption = document.createElement('div')
                    descriptionCaption.innerHTML = 'описание'
                    let descriptionInput = document.createElement('textarea')
                    descriptionInput.setAttribute('data-field', 'description')
                    descriptionInput.innerHTML = profileInfo.description
                    description.appendChild(descriptionCaption)
                    description.appendChild(descriptionInput)

                    form.appendChild(name)
                    form.appendChild(city)
                    form.appendChild(adress)
                    form.appendChild(map)
                    form.appendChild(link)
                    form.appendChild(description)

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
                                        command: 'saveDbPageObjectCard',
                                        formData,
                                        id
                                    })
                                }
                            );
                            let result = await response.text()
                            // console.log(result)
                            dbObjects.main.print()
                            dbObjects.main.show()
                        }

                        function getFormData(parent) {
                            let formData = {}
                            let formPointsArray = document.querySelectorAll(`#${parent.id} [data-field]`)
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
                                    command: 'dbPageObjectsCardGetProfileInfo',
                                    id
                                })
                            }
                        );
                        let result = await response.json()
                        return result
                        // console.log(result)
                        // dbObjects.main.print()
                        
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
            dbObjects.main.hide()
            dbObjects.newObject.hide()
            dbObjects.card.element.style.display = 'block'
        },
        hide() {
            dbObjects.card.element.style.display = 'none'
        }
    },
    newObject: {
        print() {
            this.clear(this.element)
            createCaption(this.element)
            createForm(this.element)
            createSaveButton(this.element)

            function createCaption(parent) {
                let caption = document.createElement('div')
                caption.innerHTML = 'Добавление стадиона'

                parent.appendChild(caption)

                let backButton = document.createElement('button')
                backButton.classList.add('btn-danger')
                backButton.innerHTML = '<- Назад'
                backButton.setAttribute('type', 'button')
                backButton.onclick = function() {
                    dbObjects.main.print()
                    dbObjects.main.show()
                }
                parent.appendChild(backButton)
            }

            function createForm(parent) {
                let form = document.createElement('form')

                let name = document.createElement('div')
                let nameCaption = document.createElement('div')
                nameCaption.innerHTML = 'имя'
                let nameInput = document.createElement('input')
                nameInput.setAttribute('data-field', 'name')
                name.appendChild(nameCaption)
                name.appendChild(nameInput)

                let city = document.createElement('div')
                let cityCaption = document.createElement('div')
                cityCaption.innerHTML = 'город'
                let cityInput = document.createElement('input')
                cityInput.setAttribute('data-field', 'city')
                city.appendChild(cityCaption)
                city.appendChild(cityInput)
                
                let adress = document.createElement('div')
                let adressCaption = document.createElement('div')
                adressCaption.innerHTML = 'адрес'
                let adressInput = document.createElement('input')
                adressInput.setAttribute('data-field', 'adress')
                adress.appendChild(adressCaption)
                adress.appendChild(adressInput)

                let map = document.createElement('div')
                let mapCaption = document.createElement('div')
                mapCaption.innerHTML = 'карта'
                let mapInput = document.createElement('input')
                mapInput.setAttribute('data-field', 'map')
                map.appendChild(mapCaption)
                map.appendChild(mapInput)

                let link = document.createElement('div')
                let linkCaption = document.createElement('div')
                linkCaption.innerHTML = 'link'
                let linkInput = document.createElement('input')
                linkInput.setAttribute('data-field', 'link')
                link.appendChild(linkCaption)
                link.appendChild(linkInput)

                let description = document.createElement('div')
                let descriptionCaption = document.createElement('div')
                descriptionCaption.innerHTML = 'описание'
                let descriptionInput = document.createElement('textarea')
                descriptionInput.setAttribute('data-field', 'description')
                description.appendChild(descriptionCaption)
                description.appendChild(descriptionInput)

                form.appendChild(name)
                form.appendChild(city)
                form.appendChild(adress)
                form.appendChild(map)
                form.appendChild(link)
                form.appendChild(description)

                parent.appendChild(form)
            }

            function createSaveButton(parent) {

                let saveButton = document.createElement('button')
                saveButton.classList.add('btn-danger')
                saveButton.innerHTML = 'Добавить'
                saveButton.setAttribute('type', 'button')
                saveButton.onclick = function() {
                    sendFormData(getFormData())
                    dbObjects.main.show()

                    async function sendFormData(formData) {
                        let response = await fetch(
                            '../admin/api.php', {
                                method: 'post',
                                body: JSON.stringify({
                                    command: 'newDbPageObject',
                                    formData
                                })
                            }
                        );
                        let result = await response.text()
                        console.log(result)
                        dbObjects.main.print()
                    }

                    function getFormData() {
                        let formData = {}
                        let formPointsArray = document.querySelectorAll(`#${parent.id} [data-field]`)
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
            }
        },
        clear() {
            this.element.innerHTML = ''
        },
        element: undefined,
        show() {
            dbObjects.main.hide()
            dbObjects.card.hide()
            dbObjects.newObject.element.style.display = 'block'
        },
        hide() {
            dbObjects.newObject.element.style.display = 'none'
        }
    },
}