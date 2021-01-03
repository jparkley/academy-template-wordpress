import $ from 'jquery';

class Search {
    constructor() {
        this.addSearchHTML();
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.isOverlayOpen = false;
        this.searchField = $("#search-term");
        this.resultsDiv = $("#search-overlay__results");
        this.isSpinnerVisible = false;
        this.typingTimer;
        this.previousValue;
        this.events();
    }

    // Events
    events() {
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));        
        // 'keyup': browser will have enough time to update search field value
        this.searchField.on("keyup", this.typingLogic.bind(this));   
    }
    
    // Methods
    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");       
        $("body").addClass('body-no-scroll');
        // waiting is needed because it's goint to focus before the  overlay is loaded
        setTimeout(() => this.searchField.focus(), 301);        
        this.isOverlayOpen = true;
        this.searchField.val('');
        this.resultsDiv.html('');
        return false; // To prevent default behavior of 'a' link elements
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass('body-no-scroll')
        this.isOverlayOpen = false;
    }

    keyPressDispatcher(e) {
        if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) { 
            // When the key 's' is pressed, open the overlay search
            // Also, there isn't another input or textarea focused on waiting for user input
            this.openOverlay();        
        }
        if (e.keyCode == 27 && this.isOverlayOpen) { // When the key 'esc' is pressed, close the overlay search
            this.closeOverlay();     
        }     

    }    

    typingLogic() {
        if (this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer);
            if (this.searchField.val()) {
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;                
                }  
                this.typingTimer = setTimeout(this.getResults.bind(this), 750);              
            } else {
                this.resultsDiv.html('');
            }        
        }
        this.previousValue = this.searchField.val();        
    }

    getResults() {
        // CUSTOM REST API        
        $.getJSON(academyData.root_url + "/wp-json/academy/v1/search?term=" + this.searchField.val(), (results) => {
            //console.log("result: " ,results);
            this.resultsDiv.html(`
            <div class="row">
                <div class="one-third">
                    <h2 class="search-overlay__section-title"> Posts/ Pages</h2>
                    ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No matching results</p>'}           
                    ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
                    ${results.generalInfo.length ? '</ul>' : ''}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title"> Program</h2>
                    ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No matching results.  <a href="${academyData.root_url}/programs">View all programs.</a></p>`}           
                    ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
                    ${results.programs.length ? '</ul>' : ''}                    
                    <h2 class="search-overlay__section-title"> Professors</h2>
                    ${results.professors.length ? '<ul class="professor-cards">' : `<p>No matching results.</p>`}           
                    ${results.professors.map(item => `
                    <li class="professor-card__list-item"><a class="professor-card" href="${item.permalink}">
                    <img class="professor-card__image" src="${item.image}" >
                    <span class="professor-card__name">${item.title}</span></a>
                    </li>
                    `).join('')}
                    ${results.professors.length ? '</ul>' : ''}                      
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title"> Events</h2>
                    ${results.events.length ? '' : `<p>No matching results.  <a href="${academyData.root_url}/events">View all events.</a></p>`}           
                    ${results.events.map(item => `
                    <div class="event-summary">
                    <a class="event-summary__date t-center" href="${item.permalink}">
                    <span class="event-summary__month">${item.month}</span>
                    <span class="event-summary__day">${item.day}</span>
                    </a>
                    <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                    <p>${item.desc} <a href="${item.permalink}" class="nu gray">Learn more</a></p>
                    </div>
                </div>
                    
                    `).join('')}
                  
                </div>
            </div>
            `);
            this.isSpinnerVisible = false;
        });

        // Async search using WP JSON
        // $.when(
        //     $.getJSON(academyData.root_url + "/wp-json/wp/v2/posts?search=" + this.searchField.val()), 
        //     $.getJSON(academyData.root_url + "/wp-json/wp/v2/pages?search=" + this.searchField.val())
        //     ).then((posts, pages) => {
        //     let combindResults = posts[0].concat(pages[0]);
        //     this.resultsDiv.html(`
        //     <h2 class="search-overlay__section-title">Search Results</h2>
        //     ${combindResults.length ? '<ul class="link-list min-list">' : '<p>No matching results</p>'}           
        //         ${combindResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a> ${item.type == 'post' ? `by ${item.authorName}` : ''}</li>`).join('')}
        //     ${combindResults.length ? '</ul>' : ''}
        //     `);
        //     this.isSpinnerVisible = false;                
        // }, () => {
        //     this.resultsDiv.html('<p>Unexpected error.  Please try again.</p>')
        // });        
        //
    }

    addSearchHTML() {
        $("body").append(`
        <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" id="search-term" placeholder="What are you looking for?">
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
        </div>  
        <div class="container">
          <div id="search-overlay__results"></div>
        </div>
      </div>        
        `);
    }
}

export default Search;