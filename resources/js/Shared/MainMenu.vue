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
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'

export default {
  components: {
    Icon,
  },
  methods: {
  },
}
</script>
