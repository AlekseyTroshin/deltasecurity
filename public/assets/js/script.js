document.addEventListener('DOMContentLoaded', () => {
    const body = document.body
    const parserButton = document.getElementById('parser');
    const spoilerButton = document.querySelector('.spoiler-button');
    const spoilerBlock = document.querySelector('.spoiler-block');

    const form = document.getElementById('form')
    const formInput = document.getElementById('form-input')
    const formFind = document.getElementById('form-find')
    const formClear = document.getElementById('form-clear')
    let formGenres = document.getElementById('form-genres')
    let checkboxesGenres = formGenres.querySelectorAll('.form-check-input')
    let formAuthors = document.getElementById('form-authors')
    let checkboxesAuthors = formAuthors.querySelectorAll('.form-check-input')
    const books = document.getElementById('books')

    const loadingScreenWrapper = document.querySelector('.loading-screen-wrapper');

    const keys = document.cookie
        .split("; ")
        .map(item => item.split('=', 1)[0])
    if (keys.includes('spoiler')) {
        spoilerButton.classList.add('none')
        spoilerBlock.classList.add('open-cookies')
    }

    spoilerButton.addEventListener('click', (e) => {
        e.preventDefault()
        const elem = e.currentTarget

        elem.classList.add('close')
        spoilerBlock.classList.add('open')

        setTimeout(() => {
            elem.classList.add('none')
        }, 300)

        let date = new Date(Date.now() + 600)
        document.cookie = "spoiler=true; expires=" + date
    })


    function initCheckboxes() {
        formGenres = document.getElementById('form-genres')
        checkboxesGenres = formGenres.querySelectorAll('.form-check-input')
        formAuthors = document.getElementById('form-authors')
        checkboxesAuthors = formAuthors.querySelectorAll('.form-check-input')

        checkboxesGenres.forEach(item => {
            item.addEventListener('click', checkedBoxItems)
        });

        checkboxesAuthors.forEach(item => {
            item.addEventListener('click', checkedBoxItems)
        });

        checkedBoxItems()
    }

    initCheckboxes()

    formFind.addEventListener('click', (e) => e.preventDefault())
    formInput.addEventListener('blur', () => checkedBoxItems())
    formInput.addEventListener('keydown', (e) => {
        if (e.key === "Enter") {
            checkedBoxItems()
        }
    })

    function checkedBoxItems() {
        const data = {
            'items': '',
            'search': formInput.value ? `${formInput.value}` : null
        }

        checkboxesGenres.forEach(item => {
            if (item.checked) {
                data['items'] += item.name + ','
            }
        })

        checkboxesAuthors.forEach(item => {
            if (item.checked) {
                data['items'] += item.name + ','
            }
        })

        data['items'] = data['items'].slice(0, -1)
        fetchRequest(data)

        if (data['items'] || data['search']) {
            formClear.classList.remove('none')
        } else {
            formClear.classList.add('none')
        }
    }

    formClear.addEventListener('click', (e) => {
        e.preventDefault()

        formInput.value = ''
        checkboxesGenres.forEach(item => item.checked = false)
        checkboxesAuthors.forEach(item => item.checked = false)

        e.currentTarget.classList.add('none')

        fetchRequest({})
    })

    function fetchRequest(data) {
        let url = '/search';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-Requested-With': 'XMLHttpRequest'
            },

            body: JSON.stringify({
                items: data['items'],
                search: data['search']
            })
        })
            .then(response => {
                return response.json()
            })
            .then(items => {
                renderBooks(items)
            });
    }

    function renderBooks(items) {
        let out = ''

        items.forEach(item => {
            out += `
                <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col-auto d-lg-block">
                        <img class="bd-placeholder-img"
                             width="100"
                             src="${item['img']}"
                             alt="${item['name']}">
                    </div>
                    <div class="col p-2 d-flex flex-column position-static">
                        <h4 class="mb-0">
                            ${item['name']}
                        </h4>
                        <p class="card-text mb-1">
                            ${item['description'].slice(0, 40)}...
                        </p>
                        <div class="mb-1 text-muted">автор: ${item['authors']}</div>
                        <div class="mb-1 text-muted">жанр: ${item['genres']}</div>
                        <a href="product/${item['id']}" class="stretched-link"></a>
                    </div>
                </div>
            `
        })

        books.innerHTML = out;
    }

    function renderAuthors(items) {
        let out = '<h4 class="mb-3">Авторы</h4>'

        items.forEach(item => {
            out += `
            <div class="form-check">
                <input
                    type="checkbox"
                    name="author-${item['id']}"
                    class="form-check-input rounded-pill"
                    id="author-${item['id']}"
                    value="author-${item['id']}"
                    >
                <label className="form-check-label" for="author-${item['id']}">
                    ${item['name']}
                </label>
            </div>
            `
        })

        formAuthors.innerHTML = out
    }

    function renderGenres(items) {
        let out = '<h4 class="mb-3">Жанры</h4>'

        items.forEach(item => {
            out += `
            <div class="form-check">
                <input type="checkbox"
                       name="genre-${item['id']}"
                       class="form-check-input rounded-pill"
                       id="genre-${item['id']}"
                       value="genre-${item['id']}"
                    >
                <label className="form-check-label" for="genre-${item['id']}">
                    ${item['name']}
                </label>
            </div>
            `
        })

        formGenres.innerHTML = out
    }


    parserButton.addEventListener('click', async (e) => {
        e.preventDefault()
        const url = '/parser';

        try {
            loadingScreenWrapper.classList.remove('close')
            loadingScreenWrapper.classList.add('open')

            await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    return response.json()
                })
                .then(items => {
                    renderBooks(items['books'])
                    renderAuthors(items['authors'])
                    renderGenres(items['genres'])
                    initCheckboxes()
                });
        } catch (error) {
            console.error('Error:', error)
            return []
        } finally {
            loadingScreenWrapper.classList.add('close')
            setTimeout(() => {
                loadingScreenWrapper.classList.remove('open')
            }, 300)
        }
    })

});