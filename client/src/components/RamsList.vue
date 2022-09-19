<template>
  <div class="post">
    <div v-if="loading" class="loading">Loading...</div>

    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="rams" class="content">
      <div v-for="ram in rams" v-bind:key="ram">
        <div class="card">
          <h5 class="card-header">{{ram.ram.type}}</h5>
          <div class="card-body">
            <h5 class="card-title">Special title treatment</h5>
            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
            <button type="button" @click="navigateToServer(ram.id)" class="btn btn-primary">Server Information</button>
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
  name: 'RamsList',
  props: {
    assetId: String
  },
  data() {
    return {
      loading: false,
      rams: null,
      error: null,
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      console.log(this.$route.params)
      this.error = this.rams = null
      this.loading = true

      axios.get('http://localhost:8080/server/'+this.$route.query.assetId+'/rams')
          .then((response) => {
            this.loading = false
            this.rams = response.data
            console.log(response);
          })
          .catch((error) => {
            this.loading = false
            this.error = error.toString()
            console.log(error);
          })
    },
    navigateToServer(assetId) {
      router.push({ name: 'Server', params: { assetId } })
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
