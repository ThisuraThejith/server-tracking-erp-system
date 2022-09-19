<template>
  <div class="home">
    <ServersList msg="Welcome to Your Vue.js App"/>
  </div>
</template>

<script>
// @ is an alias to /src
import ServersList from '@/components/ServersList.vue'

export default {
  name: 'Home',
  components: {
    ServersList
  }
}
</script>
