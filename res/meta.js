$(()=>{

    $('.selectAll').click( function() {
        if(!$(this).prop('checked')) {
            $('#files input').prop("checked", false);
        } else {
            $('#files input').prop("checked", true);
        }
    });


    $(".downAll").click( function() {
        $(this).attr('class', 'downAll loading');
        downloadAll()
            .then(()=>$(this).attr('class', 'downAll'))
            .catch(e=>{$('#log').html('Certains fichiers n\'ont pas pu être téléchargé');$(this).attr('class', 'downAll error')});
    });

    $(".downFile[data-method='js']").click(function() {
        let btn = $(this);
        btn.attr('class', 'downFile loading');
        download($(this).prev().find('input').attr('data-file'))
            .then(() => btn.attr('class', 'downFile success'))
            .catch(()=> btn.attr('class', 'downFile error'));
    });

    $(".downFile[data-method='html']").click(function() {
        $(this).attr('class', 'downFile success');
    });


    function downloadAll() {     
        return new Promise ((resolve, reject) => {
            $('#files').find(".file input:checked").each(function(index){
                let btn = $(this).parent().next('a.downFile');
                
                if(btn.attr('data-method') == "js") {
                    download($(this).attr('data-file'))
                    .then(() => {
                        btn.attr('class', 'downFile success');
                        
                        if(index == $('#files').find(".file input:checked").length-1) {
                            resolve();
                        }
                    })
                    .catch(()=> {btn.attr('class', 'downFile error'); reject()});
                } else {
                    $(this).parent().next('a.downFile')[0].click();
                    resolve();
                }                
            });
        });
    }

    function download(file) {
        return new Promise((resolve, reject) => {
            fetch(file)
            .then(resp => {
                console.log(resp);
                
                if(resp.status == 404) {
                    reject();
                } else {
                    resp.blob().then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        a.download = file.split('/')[3];
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        resolve()
                    }).catch(() => reject());    
                }
            })
        })
        
    }



});
    