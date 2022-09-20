<template>
  <div>
    <h1>Create Server</h1>
    <div class="jumbotron d-flex align-items-center">
      <div class="container px-50">
        <div>
          <div>
            <div class="form-group mb-3 row">
              <label for="assetId" class="col-sm-2 col-form-label form-label-text">Asset ID</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="assetId" v-model="assetId"/>
              </div>
            </div>
            <div class="form-group mb-3 row">
              <label for="brand" class="col-sm-2 col-form-label form-label-text">Brand</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="brand" v-model="brand"/>
              </div>
            </div>
            <div class="form-group mb-3 row">
              <label for="name" class="col-sm-2 col-form-label form-label-text">Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="name" v-model="name"/>
              </div>
            </div>
            <div class="form-group mb-3 row">
              <label for="price" class="col-sm-2 col-form-label form-label-text">Price</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="price" v-model="price"/>
              </div>
            </div>
            <div class="ram-form mt-4">
              <h3>Ram Modules</h3>
              <div class="row">
                <div class="col-6">
                  <div class="form-group mb-3 row">
                    <label for="ram" class="col-sm-2 col-form-label form-label-text">Ram</label>
                    <div class="col-sm-10">
                      <select class="form-control" id="ram" v-model="selectedRamId">
                        <option v-for="ram in unusedRams" v-bind:key="ram" :value=ram.id>{{ram.type + ', ' + ram.size + 'GB' }}</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group mb-3 row">
                    <label for="quantity" class="col-sm-2 col-form-label form-label-text">Quantity</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="quantity" v-model="quantity"/>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 ram-add-btn">
                <div class="form-group mb-3 row">
                  <div class="col-sm-12">
                    <button class="btn btn-primary mb-2" id="addRam" @click="addRam()">Add</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <table class="table" v-if="selectedRams.length > 0">
                <thead>
                <tr>
                  <th scope="col">Ram ID</th>
                  <th scope="col">Type</th>
                  <th scope="col">Size</th>
                  <th scope="col">Quantity</th>
                  <th scope="col"></th>
                </tr>
                </thead>
                <tbody v-for="selectedRam in selectedRams" v-bind:key="selectedRam">
                <tr>
                  <th scope="row">{{ selectedRam.id }}</th>
                  <td>{{ selectedRam.type }}</td>
                  <td>{{ selectedRam.size }}</td>
                  <td>{{ selectedRam.quantity }}</td>
                  <td><button type="button" class="btn btn-outline-danger" @click="removeRam(selectedRam.id)">Remove</button></td>
                </tr>
                </tbody>
              </table>
            </div>
            <div class="form-group row ram-add-btn mt-3">
              <div class="col-12">
                <button @click="createServer()" class="btn btn-primary mb-2" id="createServer">Create Server</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import axios from 'axios'
import router from "../router";

export default {
  data() {
    return {
      assetId: '',
      brand: '',
      name: '',
      price: '',
      quantity: null,
      selectedRamId: null,
      selectedRams: [],
      selectedRamIds: [],
      rams: []
    }
  },
  computed: {
    unusedRams: function () {
      return this.rams.filter((element) => {
        return !this.selectedRamIds.includes(element.id)
      })
    }
  },
  created() {
    this.fetchRamData()
  },
  methods: {
    fetchRamData() {
      axios.get('http://localhost:8080/rams')
          .then((response) => {
            this.rams = response.data
          })
          .catch((error) => {
            this.error = error.toString()
          })
    },
    addRam() {
      if (this.quantity && this.selectedRamId) {
        let selectedRam = this.rams.find((element) => {
          return this.selectedRamId === element.id
        })
        selectedRam.quantity = this.quantity
        this.selectedRams.push(selectedRam)
        this.selectedRamIds.push(this.selectedRamId)
        this.quantity = null
        this.selectedRamId = null
      } else {
        if (!this.quantity) {
          alert('Please add a valid quantity')
        } else if (!this.selectedRamId) {
          alert('Please select a ram module')
        }
      }
    },
    removeRam(selectedRamId) {
      let selectedRamIndex = this.selectedRams.findIndex((element) => {
        return element.id === selectedRamId
      })
      this.selectedRams.splice(selectedRamIndex, 1)

      let selectedRamIdsIndex = this.selectedRamIds.findIndex((element) => {
        return element === selectedRamId
      })
      this.selectedRamIds.splice(selectedRamIdsIndex, 1)
    },
    createServer() {
      let ram_modules = {}
      this.selectedRams.forEach((element) => {
        ram_modules[element.id.toString()] = element.quantity
      })

      let data = {
        assetId: this.assetId,
        brand: this.brand,
        name: this.name,
        price: this.price,
        ram_modules: ram_modules
      }
      axios.post('http://localhost:8080/servers',
            data
          )
          .then(function () {
            alert("Server created successfully!!!")
            router.push({ name: 'Home' })
          })
          .catch(function () {
            alert("Server creation failed!!!")
          });
    },
  },
}

</script>

<style scoped>

.form-label-text {
  text-align: right;
  font-weight: bold;
}

.ram-form {
  background-color: #ebecf0;
  padding: 10px;
  border-radius: 10px;
}

.ram-add-btn {
  text-align: right;
}

</style>