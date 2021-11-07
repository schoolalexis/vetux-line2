$(function (){
    // Show file name in bootstrap input file
    $('.custom-file-input').on('change', e => {
        let inputFile = e.currentTarget
        $(inputFile).parent().find('.custom-file-label').html(inputFile.files[0].name)
    })

    // Search customers according to their vehicle marks
    let searchMark = $("#mark")
    searchMark.on('change', () => {
        window.location.href = "customers?mark="+searchMark.val()
    })

})