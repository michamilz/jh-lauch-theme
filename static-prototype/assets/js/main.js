document.addEventListener('DOMContentLoaded', function () {
  console.log('Hi and welcome to Jugend hackt. I will be your guide!');

  document.querySelector('body').classList.add('js');

  let toc = document.querySelector('.c-toc');
  if (toc) {
    new Toc().init(toc);
  }

  new Sticky().init('.js-sticky', '.js-sticky-container')

  let slider = document.querySelectorAll('.js-slider');
  if (slider.length) {
    new Slider().init(slider);
  }

  let accordion = document.querySelectorAll('[data-accordion]');
  if (accordion.length) {
    accordion.forEach(function(item) {
      new Accordion().init(item);
    })
  }

  new IsoManagement().init('.js-isotope',
                         '.js-isotope > li',
                         '.c-filter select',
                         '.js-filter-parent');
});

function IsoManagement() {
  this.init = function (isoParent, isoChildren, selects, cats) {
    this.selects = document.querySelectorAll(selects);
    this.cats = document.querySelectorAll(cats);
    this.filterValues = {};
    var elem = document.querySelector(isoParent);
    if (elem && this.selects && this.cats) {
      this.iso = new Isotope( elem, {
        itemSelector: isoChildren,
        layoutMode: 'fitRows'
      });

      this.selects.forEach((v, i) => {
        v.addEventListener('change', this.selectEventWrapper.bind(this));
      });

      this.cats.forEach((v, i) => {
        v.addEventListener('click', this.topicEventWrapper.bind(this));
      });

      // fix layout issues by running this a bit later
      window.setTimeout(() => { this.iso.arrange(); }, 1000);
      window.setTimeout(() => { this.iso.arrange(); }, 5000);
    }
  };

  this.addToFilterValuesAndFilter = function (key, value) {
    if (value !== '' && value !== undefined) {
      this.filterValues[key] = '.'+ value;
    } else {
      this.filterValues[key] = undefined;
    }
    let filterString = Object.values(this.filterValues).join("");
    this.iso.arrange({filter: filterString});
  };

  this.selectEventWrapper = function (e) {
    this.addToFilterValuesAndFilter(e.target.id, e.target.value);
  };

  this.topicEventWrapper = function (e) {
    e.preventDefault();
    let topicFilter = document.getElementById('filter-topics');
    let value;

    if (e.target.dataset.filter) {
      value = e.target.dataset.filter;
    } else if (e.target.parentElement.dataset.filter) {
      value = e.target.parentElement.dataset.filter;
    } else if (e.target.parentElement.parentElement.dataset.filter) {
      value = e.target.parentElement.parentElement.dataset.filter;
    }

    if (value) {
      value = 'topics-' + value;
      topicFilter.value = value;
      this.addToFilterValuesAndFilter('filter-topics', value);
      //window.scrollTo(0, 300);
    }
  };
}

function Sticky() {
  this.init = function (elemSelector, parentSelector) {
    let elem = document.querySelector(elemSelector);
    let parent = document.querySelector(parentSelector);
    if (elem && parent) {
      this.sticky = elem;
      this.parent = parent;
      document.addEventListener('scroll', this.startScrollSpy.bind(this));
    }
  };
  this.startScrollSpy = function (ev) {
    let pC = this.parent.getBoundingClientRect();
    let sC = this.sticky.getBoundingClientRect();
    if (pC.top < 0) {
      let w = sC.width;
      this.sticky.style.position = 'fixed';
      this.sticky.style.top = '0';
      this.sticky.style.width = w + 'px';

      let endingSpace =  pC.top + pC.height - sC.height;
      if (endingSpace < 0) {
        this.sticky.style.top =  endingSpace +'px';
      }
    } else {
      this.sticky.style.position = 'static';
      this.sticky.style.width = '100%';
    }
  };
}

function Slider() {
  this.init = function (sliders) {
    sliders.forEach(item => {
      let options = {};

      if (item.dataset.sliderPreset === "auto") {
        options = {
          container: item,
          autoWidth: true,
          controls: false,
          autoplay: true,
          autoplayButtonOutput: false,
          gutter: 90,
          nav: false,
          autoplayTimeout: 3000
        }
      } else {
        options = {
          container: item,
          slideBy: 'page',
          fixedWidth: 350,
          nav: false,
          controlsContainer: '.tns-controls',
        }
      }
      tns(options);
    });
  };
}

function Toc(selector) {
  this.init = function (obj) {
    this.el = obj;
    this.nav = this.el.querySelector('.c-toc-nav');

    if (this.el) {
      this.nav.querySelectorAll('a')
        .forEach(x => x.addEventListener('click', this.toggleEvent.bind(this)));
      let firstElementId = this.nav.querySelector('li:first-of-type a')
          .attributes['href']['nodeValue'];
      this.activateSingle(firstElementId);
    }
  };

  this.toggleEvent = function (ev) {
    ev.preventDefault();
    this.deactivateAll();
    let activeId;
    if (ev.target.attributes.href) {
      activeId = ev.target.attributes['href']['nodeValue'];
    } else {
      activeId = ev.target.parentNode.attributes['href']['nodeValue'];
    }
    this.activateSingle(activeId);
  };

  this.deactivateAll = function () {
    [].concat(...this.el.querySelectorAll('.c-toc-nav a'))
      .concat(...this.el.querySelectorAll('.c-toc-content section'))
      .forEach(x => x.classList.remove('is-active'));
  };

  this.activateSingle = function (id) {
    let navItem = this.el.querySelector(`[href="${id}"]`);
    let contentItem = this.el.querySelector(id);

    [navItem, contentItem]
      .forEach(x => x.classList.add('is-active'));
  };
}

function Accordion() {
  this.init = function ($el) {
    this.$el = $el;
    this.$title = this.$el.querySelector('[data-title]');
    this.$content = this.$el.querySelector('[data-content]');
    this.isOpen = false;
    this.height = 0;

    this.events();
    this.close();
  };

  this.events = function() {
    this.$title.addEventListener('click', this.handleClick.bind(this));
    this.$content.addEventListener('transitionend', this.handleTransition.bind(this));
  };

  this.handleClick = function() {
    this.height = this.$content.scrollHeight;

    if (this.isOpen) {
      this.close();
    } else {
      this.open();
    }
  };

  this.close = function() {
    this.isOpen = false;
    this.$el.classList.remove('is-active');
    this.$content.style.maxHeight = `${this.height}px`;

    setTimeout(() => {
      this.$content.style.maxHeight = `${0}px`;
    }, 50);
  };

  this.open = function() {
    this.isOpen = true;
    this.$el.classList.add('is-active');
    this.$el.classList.remove('is-hidden');
    this.$content.style.maxHeight = `${0}px`;

    setTimeout(() => {
      this.$content.style.maxHeight = `${this.height}px`;
    }, 50);
  };

  this.handleTransition = function() {
    if (!this.isOpen) {
      this.$el.classList.add('is-hidden');
    }

    this.$content.style.maxHeight = '';
  };
}
