document.addEventListener('DOMContentLoaded', () => {
    const spoilerButton = document.querySelector('.spoiler-button');
    const spoilerBlock = document.querySelector('.spoiler-block');

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
        }, 500)

        let date = new Date(Date.now() + 600)
        document.cookie = "spoiler=true; expires=" + date
    })

    const form = document.getElementById('form')
    const formFind = document.getElementById('form-find')
    const formSpoiler = document.getElementById('form-spoiler')
    const formClear = document.getElementById('form-clear')
    const formGenres = document.getElementById('form-genres')
    const checkboxesGenres = formGenres.querySelectorAll('.form-check-input')
    const formAuthors = document.getElementById('form-authors')
    const checkboxesAuthors = formAuthors.querySelectorAll('.form-check-input')
    const books = document.getElementById('books')

    checkboxesGenres.forEach(item => {
        item.addEventListener('click', checkGenres)
    });

    formFind.addEventListener('click', (e) => {
        e.preventDefault()

        console.log('1')
    })

    function checkGenres() {
        let genres = '';
        checkboxesGenres.forEach(item => {
            if (item.checked) {
                genres += item.name + ','
            }
        })

        let data = genres.slice(0, -1)
        fetchRequest(data)
    }

    function fetchRequest(body) {
        let url = '/search';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                genres: body
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
                             width="140"
                             src="${item['img']}"
                             alt="${item['name']}">
                    </div>
                    <div class="col p-4 d-flex flex-column position-static">
                        <h3 class="mb-0">
                            ${item['name']}
                        </h3>
                        <p class="card-text">
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