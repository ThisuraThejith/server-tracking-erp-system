<template>
  <div class="post">
    <div v-if="loading" class="loading">Loading...</div>

    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="rams" class="content">
      <div v-for="ram in rams" v-bind:key="ram">
        <div class="card">
          <h5 class="card-header"><b>Type: </b>{{ram.ram.type}}</h5>
          <div class="card-body">
            <p class="card-text"><b>Size: </b>{{ram.ram.size}} GB</p>
            <p class="card-text"><b>Quantity: </b>{{ram.quantity}}</p>
            <button type="button" @click="deleteRam(ram.ram.id)" class="btn btn-outline-danger" :disabled="rams.length<=1">Delete Ram</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import axios from 'axios'

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
      this.error = this.rams = null
      this.loading = true

      axios.get('http://localhost:8080/server/'+this.$route.query.assetId+'/rams')
          .then((response) => {
            this.loading = false
            this.rams = response.data
          })
          .catch((error) => {
            this.loading = false
            this.error = error.toString()
          })
    },
    deleteRam(ramId) {
      let result = confirm("Are you sure you want to remove this RAM module?");
      if (result) {
        axios.delete('http://localhost:8080/server/'+this.$route.query.assetId+'/rams', {data: {ramIds: [ramId]}})
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
