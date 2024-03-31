(function (){
    var closer = document.querySelector('.sidebar-closer');
    var opener = document.querySelector('.sidebar-opener');
    var sidebar = document.querySelector('.sidebar');

    opener.addEventListener('click', function(){
       sidebar.style.display = 'flex';
    });

    closer.addEventListener('click', function(){
        sidebar.style.display = 'none';
    });
})();

