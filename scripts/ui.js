function UIControl(id, label) {
    this.id = id;
    this.label = label;

    this.value = function (value) {
        if (typeof value !== 'undefined')
            return this;
    };

    this.appendTo = function (selector = 'body') {
        if (this.hasOwnProperty('element'))
            $(selector).append(this.element);
    };

    this.prependTo = function (selector = 'body') {
        if (this.hasOwnProperty('element'))
            $(selector).prepend(this.element);
    };
}

function UITextbox(id, label, type = 'text') {
    UIControl.call(this, id, label);

    let templInput = '<input type="#type#"/>';
    let templTextbox = '<div class="textbox-container"><label for="#id#">#label#</label></div>';

    this.input = $(templInput.replace(/#id#/g, id).replace(/#type#/g, type))[0];
    this.element = $(templTextbox.replace(/#id#/g, id).replace(/#label#/g, label)).append(this.input)[0];

    this.value = function (value) {
        if (typeof value === 'undefined') {
            return this.input.value;
        }
        else {
            this.input.value = value;
            return this;
        }
    };
}

function UICheckbox(id, label) {
    UIControl.call(this, id, label);

    let templCheckbox = '<div class="checkbox-container"><div class="checkbox-clickable"></div><label>#label#</label></div>';
    this.element = $(templCheckbox.replace(/#label#/g, label))[0];

    $(this.element).find('.checkbox-clickable').click(() => {
        this.value(!this.value());
    });

    this.value = function (value) {
        let checkbox = $(this.element).find('.checkbox-clickable');

        if (typeof value === 'undefined')
            return checkbox.hasClass('checked');
        else if (value) {
            checkbox.addClass('checked');
            return this;
        }
        else {
            checkbox.removeClass('checked');
            return this;
        }
    };
}

function UIFile(id, label) {
    UIControl.call(this, id, label);

    let templFile = '<div class="file-container"><button id="#id#">#label#</button><input id="ghost-#id#" type="file"/></div>';
    this.element = $(templFile.replace(/#id#/g, id).replace(/#label#/g, label))[0];

    $(this.element).find('#' + id).click(() => {
        $(this.element).find('#ghost-' + id).trigger('click');
    });
}

function UIGroup(id) {
    this.id = id || 'group';
    const controls = [];

    this.value = function () {
        let result = {};

        for (let i = 0; i < controls.length; i++) {
            let control = controls[i];
            if (control.hasOwnProperty('id') && control.hasOwnProperty('value'))
                result[control.id] = control.value();
        }

        return result;
    };

    this.add = function (control) {
        controls.push(control);
        return control;
    };

    this.getControlById = function (id) {
        return controls.find(function (control) {
            return control.id === id;
        });
    };

    this.appendTo = function (selector) {
        for (let i = 0; i < controls.length; i++) {
            if (controls[i].hasOwnProperty('appendTo'))
                controls[i].appendTo(selector);
        }
    }

    this.prependTo = function (selector) {
        for (let i = controls.length - 1; i >= 0; i--) {
            if (controls[i].hasOwnProperty('prependTo'))
                controls[i].prependTo(selector);
        }
    }
}