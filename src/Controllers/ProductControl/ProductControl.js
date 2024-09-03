jQuery(document).ready(function ($) {
    "use strict";

    const FIELD_NAME = "wil-product";
    const CONTROL_NAME = WilokeProductFurniture.prefix + FIELD_NAME;

    async function createSelect2($el, initialData = [], action) {
        $el.select2({
            data: initialData,
            ajax: {
                url: ajaxurl,
                dataType: "json",
                data(params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                        action,
                    };
                },
                processResults(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: params.page * 30 < data.length,
                        },
                    };
                },
            },
        });
    }

    function getSelect2Value($el) {
        return $el
            .select2("data")
            .map(({ id, text }) => ({ id, text, selected: true }));
    }

    const toggleStyle = {
        hide: {
            position: "absolute",
            visibility: "hidden",
            height: "0",
        },
        show: {
            position: "static",
            visibility: "visible",
            height: "auto",
        },
    };

    const productControl = elementor.modules.controls.BaseData.extend({
        onReady() {
            this.registerDomSelector();
            this.fieldInitial();
            this.handleHiddenField();
            this.bindFieldChange();
            this.setStyles();
        },

        onBeforeDestroy() {
            if (this.$categories.select2) {
                this.$categories.select2("destroy");
            }
            if (this.$tags.select2) {
                this.$tags.select2("destroy");
            }
        },

        setStyles() {
            const styles = `
            .wil-width-100 {
                width:100%;
            }
            .wil-mb-15 {
                margin-bottom: 15px !important;
            }
          `;
            const id = "wil-internal-style";
            const el = document.getElementById(id);
            if (el) {
                return;
            }
            const style = document.createElement("style");
            style.id = id;
            style.innerHTML = styles;
            document.head.appendChild(style);
        },

        registerDomSelector() {
            this.$productNumber = this.$el.find(".wil-products-number");
            this.$categories = this.$el.find(".wil-product-categories-ids");
            this.$tags = this.$el.find(".wil-product-tags-ids");
            this.$orderBy = this.$el.find(".wil-product-order-by");
            this.$productFilter = this.$el.find(".wil-filter-product");
            this.$order = this.$el.find(".wil-product-order");
            this.$postID = this.$el.find(".wil-product-id");
            this.$products = this.$el.find(".wil-product-ids");
            this.$categoriesWrapper = this.$el.find(
                ".wil-product-categories-ids-wrapper"
            );
            this.$tagsWrapper = this.$el.find(".wil-product-tags-ids-wrapper");
            this.$productsWrapper = this.$el.find(".wil-product-ids-wrapper");
            this.$fieldGroup = this.$el.find(".wil-field-group");
        },

        handleHiddenField() {
            const settings = this.getCurrentValue();
            if (!settings) {
                return;
            }
            if (settings.filter === "manual") {
                this.$fieldGroup.css(toggleStyle.hide);
                this.$productsWrapper.css(toggleStyle.show);
            } else {
                this.$fieldGroup.css(toggleStyle.show);
                this.$productsWrapper.css(toggleStyle.hide);
            }
            if (settings.filter !== "categories") {
                this.$categoriesWrapper.css(toggleStyle.hide);
            } else {
                this.$categoriesWrapper.css(toggleStyle.show);
            }
            if (settings.filter !== "tags") {
                this.$tagsWrapper.css(toggleStyle.hide);
            } else {
                this.$tagsWrapper.css(toggleStyle.show);
            }
        },

        fieldInitial() {
            const settings = this.getCurrentValue();
            if (!settings) {
                return;
            }
            this.categoriesSelect(settings.categories);
            this.tagsSelect(settings.tags);
            this.productsSelect(settings.products);
            this.$productNumber.val(settings.limit);
            this.$order.val(settings.order);
            this.$orderBy.val(settings.orderBy);
            this.$productFilter.val(settings.filter);
        },

        handleProductFilter() {
            this.saveValue();
            this.handleHiddenField();
        },

        bindFieldChange() {
            this.$productNumber.on("change", this.saveValue.bind(this));
            this.$categories.on("change", this.saveValue.bind(this));
            this.$products.on("change", this.saveValue.bind(this));
            this.$tags.on("change", this.saveValue.bind(this));
            this.$orderBy.on("change", this.saveValue.bind(this));
            this.$productFilter.on(
                "change",
                this.handleProductFilter.bind(this)
            );
            this.$order.on("change", this.saveValue.bind(this));
            this.$postID.on("change", this.saveValue.bind(this));
        },

        saveValue() {
            this.setValue({
                limit: this.$productNumber.val(),
                categories: getSelect2Value(this.$categories),
                tags: getSelect2Value(this.$tags),
                products: getSelect2Value(this.$products),
                orderBy: this.$orderBy.val(),
                filter: this.$productFilter.val(),
                order: this.$order.val(),
                postID: this.$postID.val(),
                FIELD_NAME
            });
        },

        categoriesSelect(initialData = []) {
            createSelect2(
                this.$categories,
                initialData,
                WilokeProductFurniture.prefix + "_custom_wil_product_categories"
            );
        },

        tagsSelect(initialData = []) {
            createSelect2(
                this.$tags,
                initialData,
                WilokeProductFurniture.prefix + "_custom_wil_product_tags"
            );
        },

        productsSelect(initialData = []) {
            createSelect2(
                this.$products,
                initialData,
                WilokeProductFurniture.prefix + "_custom_wil_product"
            );
        },
    });
    elementor.addControlView(CONTROL_NAME, productControl);
});