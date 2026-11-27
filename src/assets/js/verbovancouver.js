function Verbo() {

  this.consts = {
    namespace: 'verbovancouver',
    breakpoints: {
      no: 0, 
      xs: 480,
      sm: 600,
      md: 782,
      lg: 960,
      xl: 1080,
      xxl: 1280,
      xxxl: 1440
    },
    breakpoint: 'md',
    breakpointNav: 'lg',
    transition: {
      fast: 275,
      medium: 400,
      slow: 650
    }
  };

  this.observeElements = '#site-header > *, h1, h2, h3, h4, p, .wp-block-image, .frm_forms, #site-footer > .wp-block-group, .wp-block-button';
    this.skipElements = '.skip-observing, .no-animation, .is-style-white-tr-down, .is-style-white-tr-up'; // Add your skip selectors here

  this.viewPort = {
    width: document.documentElement.clientWidth,
    height: document.documentElement.clientHeight,
    scrollbarYWidth: window.innerWidth - document.documentElement.clientWidth,
    scrollbarXHeight: window.innerHeight - document.documentElement.clientHeight
  };
  this.uniqueIdLast = 0;
  
  this.header = {
    obj: document.querySelector( 'header.page-header' ),
    height: {
      no: 97,
      md: 132
    }
  }

  this.windowPageYOffsetLast = 0;

  var st = this;
  this.isSmallScreen = function( breakpoint ) {
    breakpoint = breakpoint || st.consts.breakpoint;
    return ( st.consts.breakpoints[ breakpoint ] > st.viewPort.width );
  };
  this.isSmallScreenNav = function() {
    return ( st.consts.breakpoints[ st.consts.breakpointNav ] > st.viewPort.width + st.viewPort.scrollbarYWidth );
  };
  
  this.resize = function() {

    st.viewPort = {
      width: document.documentElement.clientWidth,
      height: window.visualViewport.height,
      scrollbarYWidth: window.innerWidth - document.documentElement.clientWidth,
      scrollbarXHeight: window.innerHeight - document.documentElement.clientHeight
    }
    
    document.documentElement.style.setProperty('--vh',st.viewPort.height*.01+'px');
    document.documentElement.style.setProperty('--vw',st.viewPort.width*.01+'px');
    document.documentElement.style.setProperty('--sw',st.viewPort.scrollbarYWidth+'px');

      var parallaxNodes = document.querySelectorAll( '.parallax-el' );
  for ( var i = 0; i < parallaxNodes.length; i++ ) {
    var node = parallaxNodes[i];
    var bp = st.consts.breakpoints[ node.dataset.parallaxBreakpoint || 'md' ];
    var isSmall = bp > st.viewPort.width;
    var mode = node.dataset.parallaxBreakpointMode || 'min';

    var shouldRun = ( 'no' === ( node.dataset.parallaxBreakpoint || 'md' ) ) ||
                    ( 'min' === mode && !isSmall ) ||
                    ( 'max' === mode && isSmall );

    if ( !shouldRun ) {
      node.style.transform = ''; // clear inline transform when effect is inactive
    }
  }

    if ( !st.isSmallScreenNav() ) {

      if ( document.documentElement.classList.contains( 'toggle-main-nav' ) ) document.documentElement.classList.remove( 'toggle-main-nav' );

    }
   // st.consts.h1Split = new SplitType('h1', {types: 'lines, words'});
  };

  this.uniqueId = function( prefix ) {
    
    var id = '' + ++st.uniqueIdLast;
    return prefix ? prefix + id : 'vv-' + id;
  };
    
  function init() {
    
    if ( window.st ) return false;

    document.documentElement.classList.add('vv-initialized');
    if ( 'ontouchstart' in document.documentElement ) document.documentElement.classList.add('vv-touch');
    if ( st.header.height.md <= window.pageYOffset ) document.documentElement.classList.add('vv-scrolling');
   // st.consts.h1Split = new SplitType('h1', {types: 'lines, words'}); 
    st.resize();
    
    return true;
  }
  init();
  
  window.st = window.st || this;
  
}
new Verbo();

( function( $ ) {

  $( function() {

    st.resize();
    st.windowPageYOffsetLast = window.pageYOffset;

    $(window).on( 'load', function() { // ON LOAD 




    } ).on( 'resize orientationchange', function() { // ON RESIZE



      st.resize();
      
    } ).on( 'scroll', function( e ) { // ON SCROLL 

     

      if ( 0 === window.pageYOffset ) document.documentElement.classList.remove( 'is-scrolling' );
      else document.documentElement.classList.add( 'is-scrolling' );

      if ( 0 === window.pageYOffset || st.windowPageYOffsetLast < window.pageYOffset ) document.documentElement.classList.remove( 'is-scrolling-up' );
      else document.documentElement.classList.add( 'is-scrolling-up' );

      st.windowPageYOffsetLast = window.pageYOffset;

    } ).on( 'hashchange', function( e ) { // ON HASHCHANGE 
      
      if ( window.location.hash.match( /^#?!\// ) ) {
        
        e.preventDefault();
        st.hashParts = window.location.hash.replace( /\/$/, '').split('/');
        
        if ( 'string' === typeof st.hashParts[1] ) {
          
          if ( 'top' === st.hashParts[1] ) {
            
            $( 'html, body' ).stop( true ).animate( { scrollTop: 0 }, st.consts.transition.slow );
            history.replaceState( undefined, document.title, window.location.pathname );
            
          } else if ( 'start' === st.hashParts[1] ) {
            
            var $target = $( '#site-main > div:nth-child(2)' );
            $( 'html, body' ).stop( true ).animate( { scrollTop: $target.offset().top - st.header.height.md }, st.consts.transition.slow );
            history.replaceState( undefined, document.title, window.location.pathname );
            $target.focus();
            
          } else if ( 'undefined' === typeof st.hashParts[2] && $( '#' + st.hashParts[1] ).length ) {
            
            var $target = $( '#' + st.hashParts[1] );
            $('html, body').stop( true ).animate( { scrollTop: $target.offset().top - st.header.height.md }, st.consts.transition.slow, function() {
              
            } );
          
          } else if ( st.hashParts[1].length && 'string' === typeof st.hashParts[2] ) {
            
            if ( 0 < st.hashParts[2].length ) {
              
              switch( st.hashParts[1] ) {
                case 'modal':
                  var modal = document.getElementById( 'vvmu-modal-content-' + st.hashParts[2] );
                  if ( modal && lity ) {
                    lity( modal );
                  }
                  history.replaceState( undefined, document.title, window.location.pathname );
                break;
              }

            }

          }

          if ( document.documentElement.classList.contains( 'toggle-main-nav' ) ) document.documentElement.classList.remove( 'toggle-main-nav' );
          if ( document.documentElement.classList.contains( 'toggle-mobile-nav' ) ) document.documentElement.classList.remove( 'toggle-mobile-nav' );
          
        }
        return false;
      } else if (window.location.hash.match( /^#?/ ) && window.location.hash.length > 1) {
        e.preventDefault();

        if ( $( window.location.hash ).length ) {
            
          var $target = $( window.location.hash );
          $('html, body').stop( true ).animate( { scrollTop: $target.offset().top - st.header.height.md }, st.consts.transition.slow, function() {
            
          } );
          if ( document.documentElement.classList.contains( 'toggle-main-nav' ) ) document.documentElement.classList.remove( 'toggle-main-nav' );
          if ( document.documentElement.classList.contains( 'toggle-mobile-nav' ) ) document.documentElement.classList.remove( 'toggle-mobile-nav' );
        }
        
      }
      
    } ).trigger( 'scroll' );
    if ( window.location.hash.length ) $( window ).trigger( 'hashchange' );

    $( '[data-toggle-id]' ).on( 'click', function( e ) {
      
      e.preventDefault();

      var id = this.dataset.toggleId;
      
      if ( document.documentElement.classList.contains( 'toggle-' + id ) ) {
        document.documentElement.classList.remove( 'toggle-' + id );
        document.getElementById( id ).setAttribute('aria-expanded', false);
      } else {
        document.documentElement.classList.add( 'toggle-' + id );
        document.getElementById( id ).setAttribute('aria-expanded', true);
      }
      
    } );



    //lity on video link//
    $('a[href]:not([data-lity])').each( function() {
      var is_video = false;
      if ( 'string' === typeof this.href ) {
        if ( -1 !== this.href.indexOf( 'youtu.be' ) ) is_video = true;
        // if ( -1 !== this.href.indexOf( '.youtube.' ) ) is_video = true;
        if ( -1 !== this.href.indexOf( '.mp4' ) ) is_video = true;
        if ( -1 !== this.className.indexOf( 'button-video' ) ) is_video = true;
        if ( -1 !== this.parentNode.className.indexOf( 'button-video' ) ) is_video = true;
      }
      if ( true === is_video && 'function' === typeof lity ) {
        if ( -1 === this.className.indexOf( 'button-video' ) ) this.className += ' button-video';
        this.setAttribute( 'data-lity-extra' , 'true' );
      }
    } );
    $( document ).on( 'click', '[data-lity-extra]', lity);

    $('.wp-block-video > video').each( function() {

      var video = this, block = this.parentNode;
      video.controls = false;

      block.classList.add( 'play-me' );
      block.addEventListener( 'click', function( e ) {

        if ( !this.classList.contains( 'play-me' ) ) return;

        e.preventDefault();
        e.stopPropagation();

        this.classList.remove( 'play-me' );
        video.controls = true;
        video.play();

      } );

    } );

    if ( 'IntersectionObserver' in window ) {
  
     var parallaxOffsetTravel = function( element ) {

  this.element = element;
  this.breakpoint = element.dataset.parallaxBreakpoint || 'md'; // 'md' = 782px, 'no' = always
  this.breakpointMode = element.dataset.parallaxBreakpointMode || 'min'; // 'min' = above, 'max' = below
  let target = this;

  // Wrap the handler so we can reference the same function when removing the listener
  this.handler = function( event ) {

    // Skip if viewport doesn't match the breakpoint requirement
    if ( 'no' !== target.breakpoint ) {
      var bp = st.consts.breakpoints[ target.breakpoint ];
      var isSmall = bp > st.viewPort.width; // true when viewport is BELOW bp

      if ( 'min' === target.breakpointMode && isSmall ) return; // only run at/above bp
      if ( 'max' === target.breakpointMode && !isSmall ) return; // only run below bp
    }

    var rect = target.element.getBoundingClientRect(), height = target.element.clientHeight;
    var offsetTop = rect.top + window.pageYOffset;

    var start = offsetTop - document.documentElement.clientHeight,
        end = offsetTop + height,
        distance = parseInt( target.element.dataset.parallaxDistance ),
        direction = target.element.dataset.parallaxDirection;

    if ( isNaN( distance ) ) distance = 100;
    if ( 'Y' !== direction ) direction = 'X';

    var travelDistance = rect.height * 2 + document.documentElement.clientHeight;
    var travelPosition = window.pageYOffset + document.documentElement.clientHeight - offsetTop;

    if ( 0 > travelPosition ) travelPosition = 0;
    if ( travelDistance < travelPosition ) travelPosition = travelDistance;

    var transformOffset = distance * ( travelPosition / travelDistance ) - ( distance / 2 );

    target.element.style.transform = 'translate' + direction + '(' + transformOffset + 'px)';
  };

  return this.handler;
};

      var observeSection = new IntersectionObserver( function( entries ) {
        
        entries.forEach( function( entry ) {
        
          var content = entry.target;
          
          if ( entry.isIntersecting ) {
            
            content.classList.add( 'intersected' );
            content.classList.add( 'intersecting' );

            if ( content.dataset.parallax && content.parallaxOffsetTravel ) window.addEventListener( 'scroll', content.parallaxOffsetTravel );

            if ( content.classList.contains( 'last-line' ) ) {
              content.parentNode.classList.add( 'intersected' );
            }

            if ( content.classList.contains( 'first-line' ) && content.parentNode.classList.contains( 'intersected' ) ) {
              content.parentNode.classList.remove( 'intersected' );
            }

          } else {

            content.classList.remove( 'intersecting' );

            if ( content.dataset.parallax && content.parallaxOffsetTravel ) window.removeEventListener( 'scroll', content.parallaxOffsetTravel );

          }
          
        });
        
      } );
      
      // Standard fade-in and slide-up.
   // Standard fade-in and slide-up.
    var contentNodes = document.querySelectorAll(st.observeElements);

for (var i = 0; i < contentNodes.length; i++) {
  // Skip if element matches any skip selector or is contained within a skipped element
  if (contentNodes[i].matches(st.skipElements) || contentNodes[i].closest(st.skipElements)) {
    continue;
  }

  if (!contentNodes[i].closest('.observing')) {
    contentNodes[i].classList.add('observing');
    if (0 !== window.pageYOffset) contentNodes[i].classList.add('intersected');
    else observeSection.observe(contentNodes[i]);

    if (contentNodes[i].classList.contains('last-line')) {
      contentNodes[i].parentNode.classList.add('observing');
      if (0 !== window.pageYOffset) contentNodes[i].parentNode.classList.add('intersected');
    }
  }
}

     // Parallax effects.
var contentNodes = document.querySelectorAll( '.parallax-el' );
for ( var i = 0; i < contentNodes.length; i++ ) {

  var node = contentNodes[i];
  node.dataset.parallax = true;

  // Defaults
  node.dataset.parallaxDistance = 200;
  node.dataset.parallaxDirection = 'Y';
  node.dataset.parallaxBreakpoint = 'md';       // default: desktop-only (≥782px)
  node.dataset.parallaxBreakpointMode = 'min';  // 'min' = at/above, 'max' = below

  // Preset named variants (keep your existing ones)
  if ( node.classList.contains( 'img-1' ) ) {
    node.dataset.parallaxDistance = 200;
    node.dataset.parallaxDirection = 'Y';
  } else if ( node.classList.contains( 'img-2' ) ) {
    node.dataset.parallaxDistance = 50;
    node.dataset.parallaxDirection = 'Y';
  } else if ( node.classList.contains( 'img-3' ) ) {
    node.dataset.parallaxDistance = -150;
    node.dataset.parallaxDirection = 'Y';
  } else if ( node.parentNode.classList.contains( 'line-1' ) ) {
    node.dataset.parallaxDistance = 25;
    node.dataset.parallaxDirection = 'X';
  } else if ( node.parentNode.classList.contains( 'line-2' ) ) {
    node.dataset.parallaxDistance = -25;
    node.dataset.parallaxDirection = 'X';
  } else if ( node.parentNode.classList.contains( 'line-3' ) ) {
    node.dataset.parallaxDistance = -50;
    node.dataset.parallaxDirection = 'Y';
  } else if ( node.classList.contains( 'compass-graphic' ) ) {
    node.dataset.parallaxDistance = 100;
    node.dataset.parallaxDirection = 'Y';
  }

  // Override: explicit utility classes win over presets.
  // Usage examples:
  //   parallax-distance-100   -> distance = 100
  //   parallax-direction-X    -> direction = X
  //   parallax-bp-no          -> ignore breakpoint (run at all sizes)
  //   parallax-bp-sm-max      -> only run BELOW 600px (mobile)
  //   parallax-bp-md-min      -> only run AT/ABOVE 782px (default behavior)
//examples:
//   parallax-el img-1	Only ≥782px (default md-min)
//   parallax-el img-1 parallax-bp-no	All screen sizes
//   parallax-el img-1 parallax-bp-sm-max	Only < 600px (mobile-only)
//   parallax-el parallax-distance-60 parallax-direction-Y parallax-bp-md-max	Mobile-only custom distance
//   parallax-el img-1 parallax-bp-lg-min	Only ≥960px (large desktop only)
  node.className.split( /\s+/ ).forEach( function( cls ) {
    var m;
    if ( ( m = cls.match( /^parallax-distance-(-?\d+)$/ ) ) ) {
      node.dataset.parallaxDistance = m[1];
    } else if ( ( m = cls.match( /^parallax-direction-([XY])$/i ) ) ) {
      node.dataset.parallaxDirection = m[1].toUpperCase();
    } else if ( ( m = cls.match( /^parallax-bp-(no|xs|sm|md|lg|xl|xxl|xxxl)(?:-(min|max))?$/ ) ) ) {
      node.dataset.parallaxBreakpoint = m[1];
      node.dataset.parallaxBreakpointMode = m[2] || 'min';
    }
  } );

  node.parallaxOffsetTravel = new parallaxOffsetTravel( node );
  observeSection.observe( node );
}
      
    }

    // Add classes and ID to page  sections
    let sectionCount = 1;
    $('#site-main').children('.wp-block-cover, .wp-block-columns, .wp-block-group').each(function() {
      $(this).addClass('section-'+sectionCount);
      if (!$(this).attr('id')) {
        $(this).attr('id', 'section-'+sectionCount);
      }
      sectionCount++;
    });
    // Rewrite scroll down arrow href on hero to next section id
    $('.verbovancouver-scroll-down').each(function() {
      let nextSiblingAnchor = '#' + $(this).next().attr('id');
      $(this).attr('href', nextSiblingAnchor);
    });

  //  Select 2
    $('.select2').find('select').select2({
      minimumResultsForSearch: Infinity,
      dropdownAutoWidth: true,
      theme: "flat",
      width: '100%'
    });

    // handle form field labels
    $('.frm_form_field input, .frm_form_field select, .frm_form_field textarea').on('change', function() {
      if ($(this).val().length > 0) {
        $(this).parent().addClass("has-val");
      } else {
        $(this).parent().removeClass("has-val");
      }
    });


    if($('.wp-block-table').length > 0) {

      $('.wp-block-table').each(function() {
        let $thisTable = $(this);
        let columnLabels = [];

        $thisTable.find('th').each(function() {
          columnLabels.push($(this).text());
        }).promise().done(function() {
          $thisTable.find('tbody tr').each(function() {
  
            $(this).find('td').each(function(i) {
              $(this).attr('data-label', columnLabels[i]);
            });
          });

        });

      });
    }

      
  } );

} )( jQuery );
/**
 * "Luz que habita": revelados al hacer scroll para cualquier elemento con data-reveal.
 *   data-reveal         → sube y aparece
 *   data-reveal="split" → el título aparece palabra por palabra (SplitType)
 *   data-reveal="mask"  → la imagen se descubre con un corte diagonal
 *   data-reveal="scale" → crece levemente al entrar
 */
( function() {

  var reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
  if ( reduce || !window.gsap || !window.ScrollTrigger ) return;

  document.documentElement.classList.add( 'vv-motion' );

  document.addEventListener( 'DOMContentLoaded', function() {

    var gsap = window.gsap;
    gsap.registerPlugin( window.ScrollTrigger );

    document.querySelectorAll( '[data-reveal]' ).forEach( function( el ) {
      if ( el.closest( '.is-preview' ) ) { el.style.opacity = 1; return; }

      var type = el.getAttribute( 'data-reveal' );
      var trigger = { trigger: el, start: 'top 88%' };

      gsap.set( el, { opacity: 1 } );

      if ( 'split' === type && window.SplitType ) {
        var split = new window.SplitType( el, { types: 'words' } );
        gsap.from( split.words, { yPercent: 110, opacity: 0, duration: 1, ease: 'expo.out', stagger: 0.06, scrollTrigger: trigger } );
      } else if ( 'mask' === type ) {
        gsap.fromTo( el, { clipPath: 'polygon(0 100%, 100% 85%, 100% 100%, 0 100%)' }, { clipPath: 'polygon(0 0, 100% 0, 100% 100%, 0 100%)', duration: 1.4, ease: 'expo.inOut', scrollTrigger: trigger } );
        var img = el.querySelector( 'img' );
        if ( img ) gsap.from( img, { scale: 1.25, duration: 1.8, ease: 'expo.out', scrollTrigger: trigger } );
      } else if ( 'scale' === type ) {
        gsap.from( el, { scale: 0.92, opacity: 0, duration: 1.2, ease: 'expo.out', scrollTrigger: trigger } );
      } else {
        gsap.from( el, { y: 40, opacity: 0, duration: 1, ease: 'expo.out', scrollTrigger: trigger } );
      }
    } );

    window.addEventListener( 'load', function() { window.ScrollTrigger.refresh(); } );
  } );

} )();
