/*jslint plusplus: true, white: true, browser: true */

if (!Array.prototype.indexOf) {
  Array.prototype.indexOf = function(item) {

    var i, length = this.length;
    for (i = 0; i < length; i++) {
      if (this[i] === item) {
        return i;
      }
    }
    return -1;
  };
}

function SelectionTagCollection(containerElement, SelectionTagConstructor, SelectionTagInputConstructor) {

  "use strict";
  var self = this,
      tags = [],
      inputObject,
      tagsContainerElement;

  function processTagRemoval(tag) {
    if (tags.indexOf(tag) >= 0) {
      tagsContainerElement.removeChild(tag.getDOMElement());
      tags.splice(tags.indexOf(tag), 1);
    }
  }

  function processTagAddition(tag) {
    tagsContainerElement.appendChild(tag.getDOMElement());
    tags.push(tag);
  }

  this.addTag = function(tagName) {
  	console.log(tagName);
  	console.log(inputObject);
  	console.log(tagsContainerElement);
    tagName = tagName.replace(/^\s+|\s+$/, '');
    
    if (tagName !== '' && self.getTagByTagName(tagName) === undefined) {
      processTagAddition(new SelectionTagConstructor(self, tagName));
    }
  };

  this.popTag = function() {
    if (tags.length) {
      return self.removeTag(tags[tags.length - 1]);
    }
  };

  this.removeTag = function(tagOrTagName) {
    var tag;

    if (tagOrTagName instanceof SelectionTagConstructor) {
      tag = tagOrTagName;
    } else if (typeof tag === 'string') {
      tag = self.getTagByTagName(tagOrTagName);
    }

    if (tag !== undefined) {
      processTagRemoval(tag);
      return tag;
    }
  };

  this.getTagByTagName = function(tagName) {
    var i, length;
    for (i = 0; i < length; i++) {
      if (tags[i].getTagName() === tagName) {
        return tags[i];
      }
    }
  };

  this.getTagNamesArray = function() {
    var i, length = tags.length, result = [];
    for (i = 0; i < length; i++) {
      result.push(tags[i].getTagName());
    }
    return result;
  };

  this.tagCount = function() {
    return tags.length;
  };

  (function() {
    inputObject = new SelectionTagInputConstructor(self, containerElement.getElementsByTagName('input')[0]);
    tagsContainerElement = containerElement.getElementsByTagName('span')[0];

    containerElement.onclick = function() {
      inputObject.getDOMElement().focus();
    };
  }());

}

function SelectionTag(tagCollection, tagName) {

  "use strict";

  var self = this,
      domElement;

  function makeDOMElement() {
    var anchor;

    domElement = document.createElement('span');
    domElement.setAttribute('class', 'selectionTag');
    domElement.appendChild(document.createTextNode(tagName));

    anchor = domElement.appendChild(document.createElement('a'));
    anchor.setAttribute('href', '#');
    anchor.setAttribute('class', 'removeLink');
    anchor.appendChild(document.createTextNode('X'));
    anchor.onclick = function() {
      tagCollection.removeTag(self);
      return false;
    };
  }

  this.getTagName = function() {
    return tagName;
  };

  this.getDOMElement = function() {
    if (domElement === undefined) {
      makeDOMElement();
    }
    return domElement;
  };

}

function SelectionTagInput(tagCollection, domElement) {

  "use strict";

  var self = this,
      noTagsMsg = '';

  function getKeyCode(e) {
    return window.event ? window.event.keyCode : e.keyCode;
  }

  function setClassNames(classNames) {
    domElement.setAttribute('class', classNames.join(' '));
  }

  function getClassNames() {
    var classNames = domElement.className.split(/\s+/);
    if (classNames.indexOf('') >= 0) {
      classNames.splice(classNames.indexOf(''), 1);
    }
    return classNames;
  }

  function addClassName(className) {
    var classNames = getClassNames();
    if (classNames.indexOf(className) < 0) {
      classNames.push(className);
    }
    setClassNames(classNames);
  }

  function removeClassName(className) {
    var classNames = getClassNames();
    if (classNames.indexOf(className) >= 0) {
      classNames.splice(classNames.indexOf(className), 1);
    }
    setClassNames(classNames);
  }

  function updateDisplay(hasTags) {
    if (hasTags) {
      removeClassName('noTags');
      if (tagCollection.tagCount()) {
        removeClassName('firstTag');
      } else {
        addClassName('firstTag');
      }
      if (domElement.value === noTagsMsg) {
        domElement.value = '';
      }
    } else {
      addClassName('noTags');
      addClassName('firstTag');
      domElement.value = noTagsMsg;
    }
  }

  function addTag() {
    tagCollection.addTag(domElement.value);
    domElement.value = '';
    removeClassName('firstTag');
  }

  function removeLastTag() {
    var tag = tagCollection.popTag();
    if (tag) {
      domElement.value = tag.getTagName();
    }
    if (!tagCollection.tagCount()) {
      addClassName('firstTag');
    }
  }

  this.getDOMElement = function() {
    return domElement;
  };

  (function() {
    updateDisplay(false);
    addClassName('firstTag');

    domElement.onfocus = function() {
      if (!tagCollection.tagCount()) {
        updateDisplay(true);
      }
    };

    domElement.onblur = function() {
      if (domElement.value !== '' && domElement.value !== noTagsMsg) {
        addTag();
      }
      if (!tagCollection.tagCount()) {
        updateDisplay(false);
      }
    };

    domElement.onkeyup = function(e) {
      if ([13, 32].indexOf(getKeyCode(e)) >= 0) {
        addTag();
      } else if (getKeyCode(e) === 8 && domElement.value === '') {
        removeLastTag();
      }
    };
  }());

}

function bootstrap() {

  var tagCollection = new SelectionTagCollection(document.getElementById('tagsContainer'), SelectionTag, SelectionTagInput);
  
  var tagCollection2 = new SelectionTagCollection(document.getElementById('tagsContainer2'), SelectionTag, SelectionTagInput);

 document.getElementById('searchForm').onsubmit = function() {
	 var ct = document.getElementById('tagsContainer');
	 var tags = tagCollection.getTagNamesArray();
	 console.log(tags);
	 for ( var i in tags) {
		 console.log(i);
		 var input = document.createElement('input');
		 input.type = "hidden";
		 input.name = "k[]";
		 input.value = tags[i];
		 ct.appendChild(input);
	 }

	 return true;
 };
 
 document.getElementById('publishForm').onsubmit = function() {
	 var ct = document.getElementById('tagsContainer2');
	 var tags = tagCollection2.getTagNamesArray();
	 console.log(tags);
	 for ( var i in tags) {
		 console.log(i);
		 var input = document.createElement('input');
		 input.type = "hidden";
		 input.name = "k[]";
		 input.value = tags[i];
		 ct.appendChild(input);
	 }

	 return true;
 };

}


$("#search, #post").live("pagecreate", function() {
	bootstrap();
});