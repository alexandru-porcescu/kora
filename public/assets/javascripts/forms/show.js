var Kora = Kora || {};
Kora.Forms = Kora.Forms || {};

Kora.Forms.Show = function() {
  function clearSearch() {
    $('.search-js .icon-cancel-js').click();
  }

  function initializeSearch() {
    var $searchInput = $('.search-js input');

    $('.search-js i, .search-js input').click(function(e) {
      e.preventDefault();

      $(this).parent().addClass('active');
      $('.search-js input').focus();
    });

    $searchInput.focusout(function() {
      if (this.value.length == 0) {
        $(this).parent().removeClass('active');
        $(this).next().removeClass('active');
      }
    });

    $searchInput.keyup(function(e) {
      if (e.keyCode === 27) {
        $(this).val('');
      }

      if (this.value.length > 0) {
        $(this).next().addClass('active');
      } else {
        $(this).next().removeClass('active');
      }
    });

    $('.search-js .icon-cancel-js').click(function() {
      $searchInput.val('').blur().parent().removeClass('active');
    });
  }

  function initializeFieldSort() {
    // Initialize Custom Sort
    $('.field-toggle-js').click(function(e) {
      e.preventDefault();

      var $this = $(this);
      var $header = $this.parent().parent();
      var $field = $header.parent();
      var $content = $header.next();

      $this.children('.icon').toggleClass('active');
      $field.toggleClass('active');
      if ($field.hasClass('active')) {
        $header.addClass('active');
        $field.animate({
          height: $field.height() + $content.outerHeight(true) + 'px'
        }, 230);
        $content.effect('slide', {
          direction: 'up',
          mode: 'show',
          duration: 240
        });
      } else {
        $field.animate({
          height: '58px'
        }, 230, function() {
          $header.hasClass('active') ? $header.removeClass('active') : null;
          $content.hasClass('active') ? $content.removeClass('active') : null;
        });
        $content.effect('slide', {
          direction: 'up',
          mode: 'hide',
          duration: 240
        });
      }

    });

    $(".page-sort-js").sortable({
      helper: 'clone',
      revert: true,
      containment: ".form-show",
      items: '.field-container',
      update: function(event, ui) {
        pidsArray = $(".page-sort-js").sortable("toArray");

        // $.ajax({
        //   url: saveCustomOrderUrl,
        //   type: 'POST',
        //   data: {
        //     "_token": CSRFToken,
        //     "pids": pidsArray,
        //
        //   },
        //   success: function(result) {}
        // });
      }
    });

    $('.move-action-js').click(function(e) {
      e.preventDefault();

      var $this = $(this);
      var $headerInnerWrapper = $this.parent().parent();
      var $header = $headerInnerWrapper.parent();
      var $field = $header.parent();
      var $fieldContainer = $field.parent();
      // $field.prev().before(current);
      if ($this.hasClass('up-js')) {
        var $previousField = $fieldContainer.prev();
        if ($previousField.length == 0 || !$previousField.hasClass('field-container')) {
          // move to previous page if exists
          return;
        }

        $previousField.css('z-index', 999)
          .css('position', 'relative')
          .animate({
            top: $field.height()
          }, 300);
        $fieldContainer.css('z-index', 1000)
          .css('position', 'relative')
          .animate({
            top: '-' + $previousField.height()
          }, 300, function() {
            $previousField.css('z-index', '')
              .css('top', '')
              .css('position', '');
            $fieldContainer.css('z-index', '')
              .css('top', '')
              .css('position', '')
              .insertBefore($previousField);
          });
      } else {
        var $nextField = $fieldContainer.next();
        if ($nextField.length == 0 || !$nextField.hasClass('field-container')) {
          // move to next page if exists
          return;
        }

        $nextField.css('z-index', 999)
          .css('position', 'relative')
          .animate({
            top: '-' + $field.height()
          }, 300);
        $fieldContainer.css('z-index', 1000)
          .css('position', 'relative')
          .animate({
            top: $nextField.height()
          }, 300, function() {
            $nextField.css('z-index', '')
              .css('top', '')
              .css('position', '');
            $fieldContainer.css('z-index', '')
              .css('top', '')
              .css('position', '')
              .insertAfter($nextField);
          });
      }
    });
  }

  function initializePage() {
    pageTitles = document.getElementsByClassName('page-title-js');
    for (var i = 0; i < pageTitles.length; i++) {
      $pageTitle = pageTitles[i];
      $pageTitle.setAttribute('size',
        $pageTitle.getAttribute('placeholder').length);
    }
  }

  initializeSearch();
  initializePage();
  initializeFieldSort();
}