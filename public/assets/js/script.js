document.addEventListener('DOMContentLoaded', () => {
    const spoilerButton = document.querySelector('.spoiler-button');
    const spoilerBlock = document.querySelector('.spoiler-block');

    const form = document.getElementById('form')
    const formInput = document.getElementById('form-input')
    const formFind = document.getElementById('form-find')
    const formClear = document.getElementById('form-clear')
    const formGenres = document.getElementById('form-genres')
    const checkboxesGenres = formGenres.querySelectorAll('.form-check-input')
    const formAuthors = document.getElementById('form-authors')
    const checkboxesAuthors = formAuthors.querySelectorAll('.form-check-input')
    const books = document.getElementById('books')

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

    checkboxesGenres.forEach(item => {
        item.addEventListener('click', checkedBoxItems)
    });

    checkboxesAuthors.forEach(item => {
        item.addEventListener('click', checkedBoxItems)
    });

    formFind.addEventListener('click', (e) => e.preventDefault())

    formInput.addEventListener('blur', () => checkedBoxItems())
    formInput.addEventListener('keydown', (e) => {
        if (e.key === "Enter") {
            checkedBoxItems()
        }
    })

    checkedBoxItems()
    function checkedBoxItems() {
        const data = {
            'items' : '',
            'search' : formInput.value ? `${formInput.value}` : null
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
        let out = '';

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
                            ${item['description']}
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

});

// authors
//     :
//     "Джоан Роулинг ,Лев Николаевич Толстой,Фёдор Михайлович Достоевский"
// description
//     :
//     "Эпический роман о войне 1812 года"
// genres
//     :
//     "Исторический роман,Роман"
// id
//     :
//     1
// img
//     :
//     "/assets/img/books/war_and_peace.jpg"
// name
//     :
//     "Война и мир"