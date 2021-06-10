<template>
  <div class="pl-14 transition-spacing" :class="[collapsed? 'collapsed' : 'sm:pl-96']">
    <div
      v-if="!collapsed"
      class="fixed w-screen h-screen top-0 left-0 bg-gray-600 opacity-50 z-10 sm:hidden"
      @click="collapsed = true"
    />
    <sidebar-menu
      class="left-0 bg-black h-screen fixed text-white text-2xl font-bold z-50 transition-width"
      :class="[collapsed? 'w-14' : 'w-80']"
      :menu="menu"
      :collapsed="collapsed"
      :show-one-child="true"
      @toggle-collapse="onToggleCollapse"
      @item-click="onItemClick"
    />
    <div class="flex-1 px-4 py-8 transition-spacing" :class="[collapsed? '' : '']" scroll-region>
      <flash-messages />
      <slot />
    </div>
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import SidebarMenu from '@/Menus/Components/SidebarMenu'
import FlashMessages from '@/Shared/FlashMessages'

const separator = {
  template: `<hr class="border-gray-300 m-5">`
}

export default {
  components: {
    FlashMessages,
    Icon,
    SidebarMenu,
  },
  data() {
    return {
      menu: [
        {
          header: true,
          title: 'Getting Started',
          hiddenOnCollapse: true
        },
        {
          href: '/',
          title: 'Installation',
          icon: 'fa fa-download'
        },
        {
          href: '/basic-usage',
          title: 'Basic Usage',
          icon: 'fa fa-code'
        },
        {
          header: true,
          title: 'Usage',
          hiddenOnCollapse: true
        },
        {
          href: '/props',
          title: 'Props',
          icon: 'fa fa-cogs'
        },
        {
          href: '/events',
          title: 'Events',
          icon: 'fa fa-bell'
        },
        {
          href: '/styling',
          title: 'Styling',
          icon: 'fa fa-palette'
        },
        {
          component: separator
        },
        {
          header: true,
          title: 'Example',
          hiddenOnCollapse: true
        },
        {
          href: '/disabled',
          title: 'Disabled page',
          icon: 'fa fa-lock',
          disabled: true
        },
        {
          title: 'Badge',
          icon: 'fa fa-cog',
          badge: {
            text: 'new',
            class: 'vsm--badge_default'
          }
        },
        {
          href: '/page',
          title: 'Dropdown Page',
          icon: 'fa fa-list-ul',
          child: [
            {
              href: '/page/sub-page-1',
              title: 'Sub Page 01',
              icon: 'fa fa-file-alt'
            },
            {
              href: '/page/sub-page-2',
              title: 'Sub Page 02',
              icon: 'fa fa-file-alt'
            }
          ]
        },
        {
          title: 'Multiple Level',
          icon: 'fa fa-list-alt',
          child: [
            {
              title: 'page'
            },
            {
              title: 'Level 2 ',
              child: [
                {
                  title: 'page'
                },
                {
                  title: 'Page'
                }
              ]
            },
            {
              title: 'Page'
            },
            {
              title: 'Another Level 2',
              child: [
                {
                  title: 'Level 3',
                  child: [
                    {
                      title: 'Page'
                    },
                    {
                      title: 'Page'
                    }
                  ]
                }
              ]
            }
          ]
        }
      ],    
      collapsed: true,
    }
  },
  mounted () {
    this.onResize()
    window.addEventListener('resize', this.onResize)
  },
  methods: {
    onToggleCollapse (collapsed) {
      this.collapsed = collapsed
    },
    onItemClick (event, item, node) {
      // console.log('onItemClick')
      // console.log(event)
      // console.log(item)
      // console.log(node)
    },  
    onResize () {
      this.collapsed = (window.innerWidth <= 767);
    }
  },
}
</script>