export const tabs = {
    template: `
        <div>
        <div class="tabs">
            <ul>
                <li v-for="tab in tabs" :class="{ 'is-active': tab.isActive, 'tab-has-errors': tab.hasError }" :data-tab-name="tab.name">
                    <a :href="tab.href" @click="selectTab(tab)">
                        <i v-if="isMobile && tab.iconClass" class="fa" :class="'fa-' + tab.iconClass"></i>
                        <template v-else>{{ tab.label }}</template>
                    </a>
                </li>
            </ul>
        </div>
        <h5 v-if="isMobile && activeLabel" class="tabs-header">{{ activeLabel }}</h5>
        <div class="tabs-details">
            <slot></slot>
        </div>
        </div>
    `,
    data() {
        return {
            tabs: [],
            activeTab: null,
            activeLabel: null,
            isMobile: false
        }
    },
    methods: {
        selectTab: function (selectedTab) {
            if(!selectedTab){
                return;
            }
            let oldTab = this.activeTab;
            this.activeTab = selectedTab.name;
            this.activeLabel = selectedTab.label;
            this.tabs.forEach(tab => {
                tab.isActive = (tab.name == selectedTab.name);
            });
            this.$emit('change', selectedTab.name, oldTab);
            this.$nextTick(function () {
                if (window.hasOwnProperty('__textareautosize')) {
                    window.__textareautosize()
                }
            });

        }
    },
    mounted() {
        if (document.location.hash) {
            let hash = document.location.hash;
            let activeTab = this.tabs[0];
            for (let tab of this.tabs) {
                if (hash == tab.href) {
                    activeTab = tab;
                    break;
                }
            }
            if (activeTab !== null) {
                this.selectTab(activeTab);
            }
        } else {
            this.selectTab(this.tabs[0]);
        }
    }
};

export const tab = {
    props: {
        name: {required: true},
        label: {required: true},
        selected: {default: false},
        iconClass: {default: null},
        hasError:  false
    },
    template: `
        <div v-show="isActive">
        <slot></slot>
        </div>`,
    data() {
        return {isActive: false}
    },
    computed: {
        href() {
            return '#' + this.name.toLowerCase().replace(/ /g, '-');
        }
    },
    created() {
        this.$parent.tabs.push(this);
    },
    mounted() {
        this.isActive = this.selected;
    }
}
