/* Funciones comunes a toda la aplicación*/

function modalPages() {
    /* Modal pages */
    $(".ajax").colorbox({iframe: true, width: "950px", height: "80%"});
    $(".iframe").colorbox({iframe: true, width: "500px", height: "80%"});
    /* Modal pages appened elements */
    $('#container').on('click', '.ajax', function() {
        $(this).colorbox({iframe: true, width: "950px", height: "80%"});
    });
    $('#container').on('click', '.iframe', function() {
        $(this).colorbox({iframe: true, width: "500px", height: "80%"});
    });
}

function menuSlidedown() {
    /* Slidedown menu */
    $('#nav li').hover(
            function() {
                $('ul', this).stop(true, true).slideDown(100);
            },
            function() {
                $('ul', this).stop(true, true).slideUp(100);
            });
}

function menuActions() {
    $(".menuLink").click(function() {
        return false;
    });
    $('.menuOption').on('mouseenter',
            function()
            {
                var url = $(this).children("a:first").attr("href");
                var $this = $(this);
                $.post(url, {
                    other: "attributes"
                }, function(data) {
                    $this.append($(data));
                });
            }
    );
}

function backToTop() {
    $(window).scroll(function() {
        if ($(this).scrollTop() !== 0) {
            $('#footer-back').fadeIn();
        }
        else {
            $('#footer-back').fadeOut();
        }
    });
    $('#footer-back').click(function() {
        $('body,html').animate({scrollTop: 0}, 800);
    });
}

function loadElements($container, elementClass, urlIn, messageOut) {
    /* Loading more publications */
    $(window).scroll(function() {
        if ($(window).scrollTop() === $(document).height() - $(window).height()) {
            $('#footer-loading').fadeIn();
            lastElement = $(elementClass).last().attr('id');
            var url = urlIn;
            url = url.replace('+lastElement+', lastElement);
            $.post(url, {
                other: "attributes"
            }, function(data) {
                if ($(data).size() > 0) {
                    var $elements = $(data);
                    //$container.append($elements).masonry('appended', $elements);
                    $container.append($elements);
                    $container.masonry('appended', $elements);
                    $container.imagesLoaded(function() {
                        $container.masonry('reload');
                    });
                    $('#footer-loading').fadeOut();
                }
                else
                {
                    $('#footer-loading').html('<p><strong>' + messageOut + '</strong></p>');
                }
            });
        }
        else {
            $('#footer-loading').fadeOut();
        }
    });
}

function imageFile() {
    $(function() {
        $(".board-custom-input-file input:file").change(function()
        {
            $(this).parent().find(".file").html($(this).val());
        });
    });
    $(document).ready(function()
    {
        $('.file').preimage();
    });
}

function enableSelectBoxes() {
    $('div.selectBox').each(function() {
        $(this).parent().children('div.selectOptions').css('display', 'none');
        $(this).children('span.selected,span.selectArrow').click(function() {
            if ($(this).parent().children('div.selectOptions').css('display') === 'none') {
                $(this).parent().children('div.selectOptions').css('display', 'block');
            }
            else
            {
                $(this).parent().children('div.selectOptions').css('display', 'none');
            }
        });
        $(this).find('span.selectOption').click(function() {
            $(this).parent().css('display', 'none');
            $(this).closest('div.selectBox').attr('id', $(this).attr('id'));
            $(this).parent().siblings('span.selected').html($(this).html());
        });
    });
}

function newBoard(urlIn) {
    /* Creación de una nueva carpeta */
    $('#newBoardAction').click(function() {
        var url = urlIn;
        var name = $('#newBoardName').val();
        $.post(url, {
            boardName: name,
            other: 'attributes'
        }, function(data) {
            $('#selectOptions span:last').before($(data));
            $('#newBoardName').val('');
            enableSelectBoxes();
            $('.selectOption:last').click();
        });
        enableSelectBoxes();
    });
}

function publicationActions($container) {
    /* Show and hide action buttons for publications */
    $container.on('mouseenter', '.pub', (
            function()
            {
                $('.pub-actions', this).show();
            })
            );
    $container.on('mouseleave', '.pub', (
            function()
            {
                $('.pub-actions', this).hide();
            })
            );
    /* Actions for unshare publication */
    $container.on('click', '.buttonUnshare', function() {

        var url = $(this).attr("href");
        $.post(url, {
            other: "attributes"
        }, function(data) {
            $('.buttonUnshare' + data.id).css({'display': 'none'});
            $('.buttonShare' + data.id).css({'display': 'inline'});
            $('.spanShared' + data.id).css({'display': 'none'});
            $('.numShared' + data.id).text(data.shares);
        });
        return false;
    });
}

function publicationSave(urlIn, urlBoardIn) {
    /* Guarda la publicación en la carpeta seleccionada */
    $('#buttonSave').click(function() {
        var url = urlIn;
        var folder = $('.selectBox').attr('id');
        url = url.replace("+boardElement+", folder);
        $.post(url, {
            other: 'attributes'
        }, function(data) {
            window.parent.$('.buttonSave' + data.id).css({'display': 'none'});
            window.parent.$('.buttonEdit' + data.id).css({'display': 'inline'});
            window.parent.$('.spanSaved' + data.id).css({'display': 'inline'});
            window.parent.$('.numSaved' + data.id).text(data.saves);
            if (data.boardId) {
                var urlBoard = urlBoardIn;
                urlBoard = urlBoard.replace("+boardElement+", data.boardId);
                window.parent.$('#board' + data.id).html(
                        '<a href="' + urlBoard + '"><strong>' + data.boardName + '</strong></a>'
                        );
            }

            parent.$.fn.colorbox.close();
        });
        return false;
    });
}

function publicationDelete(urlIn) {
    /* Elimina un publicación guardada previamente */
    $("#buttonDelete").click(function() {
        var url = urlIn;
        $.post(url, {
            other: "attributes"
        }, function(data) {
            window.parent.$('.buttonSave' + data.id).css({'display': 'inline'});
            window.parent.$('.buttonEdit' + data.id).css({'display': 'none'});
            window.parent.$('.spanSaved' + data.id).css({'display': 'none'});
            window.parent.$('.numSaved' + data.id).text(data.saves);
            parent.$.fn.colorbox.close();
        });
        return false;
    });
}

function publicationShare() {
    $("#shareButton").click(function() {

        var url = $("#shareButton").attr("href");
        $.post(url, {
            other: "attributes"
        }, function(data) {
            window.parent.$('.buttonShare' + data.id).css({'display': 'none'});
            window.parent.$('.buttonUnshare' + data.id).css({'display': 'inline'});
            window.parent.$('.spanShared' + data.id).css({'display': 'inline'});
            window.parent.$('.numShared' + data.id).text(data.shares);
            parent.$.fn.colorbox.close();
        });
        return false;
    });
}

function publicationImage() {
    $(function() {
        $(".pub-custom-input-file input:file").change(function()
        {
            $('.prev_container').show();
            if (document.getElementById("pubphoto") !== null)
            {
                $('#pubphoto').remove();
            }
            $(this).parent().find(".file").html($(this).val());
        });
    });
    $(document).ready(function()
    {
        $('.file').preimage();
    });
}

function deleteComment() {
    /* Elimina un comentario publicado previamente */
    $(".deleteComment").click(function() {
        var url = $(this).attr('href');
        alert(url);
        $.post(url, {
            other: "attributes"
        }, function(data) {
            $('#divComment' + data.commentId).remove();
            $('#totalComments').text(data.comments);
            $('.numComments' + data.id).text(data.comments);
            window.parent.$('.numComments' + data.id).text(data.comments);
        });
        return false;
    });
}

function sportSelect(urlModaIn, urlIn) {
    $("select").change(function() {
        var str = "";
        $("select option:selected").each(function() {
            str = $(this).val();
        });
        if (str) {
            var url = urlModaIn;
            url = url.replace("+modalityId+", str);
        }
        else {
            var url = urlIn;
        }
        window.location.href = url;
    });
}

function sportActions($container) {
    /* Show unfollow button */
    $container.on({
        mouseenter: function() {
            $(this).text("Dejar de seguir");
            $(this).attr("class", "buttonUnfollowSport button-red");
        },
        mouseleave: function() {
            $(this).text("Siguiendo");
            $(this).attr("class", "buttonUnfollowSport button-blue");
        }
    }, ".buttonUnfollowSport");
    /* Actions for follow sport */
    $container.on('click', '.buttonFollowSport', function() {

        var url = $(this).attr("href");
        $.post(url, {
            other: "attributes"
        }, function(data) {
            $('#buttonUnfollowSport' + data.id).css({'display': 'inline-block'});
            $('#buttonFollowSport' + data.id).css({'display': 'none'});
            $('#numFollowersSport' + data.id).text(data.followers);
        });
        return false;
    });
    /* Actions for unfollow sport */
    $container.on('click', '.buttonUnfollowSport', function() {

        var url = $(this).attr("href");
        $.post(url, {
            other: "attributes"
        }, function(data) {
            $('#buttonUnfollowSport' + data.id).css({'display': 'none'});
            $('#buttonFollowSport' + data.id).css({'display': 'inline-block'});
            $('#numFollowersSport' + data.id).text(data.followers);
        });
        return false;
    });
}

function userActions($container) {
    /* Show unfollow button */
    $container.on({
        mouseenter: function() {
            $(this).text("Dejar de seguir");
            $(this).attr("class", "element-button-a buttonUnfollow button-red");
        },
        mouseleave: function() {
            $(this).text("Siguiendo");
            $(this).attr("class", "element-button-a buttonUnfollow button-blue");
        }
    }, ".buttonUnfollow");
    /* Actions for follow user */
    $container.on('click', '.buttonFollow', function() {

        var url = $(this).attr("href");
        $.post(url, {
            other: "attributes"
        }, function(data) {
            $('#buttonUnfollow' + data.id).css({'display': 'inline-block'});
            $('#buttonLists' + data.id).css({'display': 'inline-block'});
            $('#buttonFollow' + data.id).css({'display': 'none'});
            $('#numFollowers' + data.id).text(data.followers);
        });
        return false;
    });
    /* Actions for unfollow user */
    $container.on('click', '.buttonUnfollow', function() {
        
        var url = $(this).attr("href");
        $.post(url, {
            other: "attributes"
        }, function(data) {
            $('#buttonUnfollow' + data.id).css({'display': 'none'});
            $('#buttonLists' + data.id).css({'display': 'none'});
            $('#buttonFollow' + data.id).css({'display': 'inline-block'});
            $('#numFollowers' + data.id).text(data.followers);
        });
        return false;
    });
}
