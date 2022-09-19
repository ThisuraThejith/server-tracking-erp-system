<template>
  <div class="post">
    <div v-if="loading" class="loading">Loading...</div>

    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="servers" class="content">
      <div v-for="server in servers" v-bind:key="server">
        <div class="card">
          <h5 class="card-header">{{server.assetId}}</h5>
          <div class="card-body">
            <h5 class="card-title">Special title treatment</h5>
            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
            <button type="button" @click="navigateToServer(server.assetId)" class="btn btn-primary">Server Information</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import router from '../router'

export default {
  name: 'ServersList',
  data() {
    return {
      loading: false,
      servers: null,
      error: null,
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.error = this.servers = null
      this.loading = true

      axios.get('http://localhost:8080/servers')
          .then((response) => {
            this.loading = false
            this.servers = response.data
            console.log(response);
          })
          .catch((error) => {
            this.loading = false
            this.error = error.toString()
            console.log(error);
          })
    },
    navigateToServer(assetId) {
      router.push({ name: 'Server', query: { assetId } })
      console.log(assetId);
    },
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
