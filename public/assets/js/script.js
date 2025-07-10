window.addEventListener('load', () => {

    const spolerButton = document.querySelector('.spoiler-button');
    const spolerBlock = document.querySelector('.spoiler-block');

    spolerButton.addEventListener('click', (e) => {
        e.preventDefault()
        const elem = e.currentTarget
        elem.classList.add('close')
        spolerBlock.classList.add('open')
        setTimeout(() => {
            elem.classList.add('none')
        }, 500)
    })

});
