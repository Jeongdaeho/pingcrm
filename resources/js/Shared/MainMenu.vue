<template>
  <div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('dashboard')">
        <icon name="dashboard" class="w-4 h-4 mr-2" :class="route().current('') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="route().current('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Dashboard</div>
      </inertia-link>
    </div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('users.index')">
        <icon name="users" class="w-4 h-4 mr-2" :class="route().current('users.*') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="route().current('users.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">회원관리</div>
      </inertia-link>
    </div>
    <ul class="text-white main-nav">
      <li class="bg-blue-300">
        <a class="">봇관리</a>
        <ul>
          <li class="bg-pink-400">
            <a >챗봇 관리</a>
            <ul v-if="false" class="transform tran">
              <li class="bg-green-300"><a>질문관리</a></li>
              <li class="bg-green-300"><a>질문관리</a></li>
              <li class="bg-green-300"><a>질문관리</a></li>
              <li class="bg-green-300"><a>질문관리</a></li>
              <li class="bg-green-300"><a>질문관리</a></li>
            </ul>
          </li>
          <li class="bg-pink-400">
            <a>챗봇 관리</a>
            <ul v-if="false">
              <li class="bg-green-300"><a>질문관리</a></li>
              <li class="bg-green-300"><a>질문관리</a></li>
              <li class="bg-green-300"><a>질문관리</a></li>
              <li class="bg-green-300"><a>질문관리</a></li>
            </ul>
          </li>
        </ul> 
      </li>
    </ul>
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'

export default {
  components: {
    Icon,
  },
  props:{

  },
  data:{
    
  },
  methods: {
  }
}
</script>

<style>
  .main-nav a{
    padding: 0.6em;
    display:block;
  }
  .main-nav ul{
    padding-left: 1em;
    padding-bottom:0.5em;
  }
</style>