<template>
  <div class="post">
    <div v-if="loading" class="loading">Loading...</div>

    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="servers" class="content">
      <div v-for="server in servers" v-bind:key="server">
        <div class="card">
          <h5 class="card-header"><b>Asset ID: </b>{{server.assetId}}</h5>
          <div class="card-body">
            <p class="card-text"><b>Brand: </b>{{server.brand}}</p>
            <p class="card-text"><b>Name: </b>{{server.name}}</p>
            <p class="card-text"><b>Price: </b>{{server.price}}</p>
            <button type="button" @click="navigateToServer(server.assetId)" class="btn btn-primary mx-2">Ram Details</button>
            <button type="button" @click="deleteServer(server.assetId)" class="btn btn-outline-danger">Delete Server</button>
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
          })
          .catch((error) => {
            this.loading = false
            this.error = error.toString()
          })
    },
    navigateToServer(assetId) {
      router.push({ name: 'Server', query: { assetId } })
    },
    deleteServer(assetId) {
      let result = confirm('Are you sure you want to delete this server?')
      if (result) {
        axios.delete('http://localhost:8080/servers', {data: {assetIds: [assetId]}})
            .then(() => {
              this.fetchData()
            })
            .catch((error) => {
              this.error = error.toString()
            })
      }
    },
  },
}

</script>

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
