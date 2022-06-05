<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2 class="float-start">Short Url Generatior</h2>
                        <button class="btn btn-sm float-end btn-danger"  id="basic-addon2" @click="resetForm" v-if="long_url">Reset</button>


                    </div>

                    <div class="card-body">
                        <form ref="form" method="post" @submit.prevent="SubmitForm">
                            <div class="input-group mb-12">
                                <input v-model="long_url" type="text" class="form-control" placeholder="Enter Your URL">
                                <button class="input-group-text btn-success"  id="basic-addon2" type="submit">Submit</button>

                           </div>
                       </form>


                    </div>




                </div>
                <div class="card mt-5" v-if="data.url">
                      <div class="card-body">

                        <div v-if="data.step == 'Invalid'">
                            <div v-if="data.url">

                                <div class="card">
                                    <div class="card-header">Copy
                                        <i class="float-end mdi mdi-content-copy" style="cursor:pointer" @click="copText"></i>
                                    </div>
                                    <div class="card-body">
                                        {{ data.url}}
                                    </div>
                                </div>


                            </div>


                        </div>
                        <div v-else-if="data.step == 'Done'">


                                <div class="card">
                                    <div class="card-header">Copy
                                        <i class="float-end mdi mdi-content-copy" style="cursor:pointer" @click="copText"></i>
                                    </div>
                                    <div class="card-body">
                                        {{ data.url}}
                                    </div>
                                </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <notifications group="foo" position="top center" width="800" />
    </div>

</template>

<script>
    export default {
        data() {
            return {
                long_url: "",
                data : [],
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            SubmitForm(){
                axios.post('/store', {
                    long_url: this.long_url,
                })


                .then(response =>  {
                    // console.log(response.data.message);
                    this.data = response.data;
                    var type = 'success'
                    if(response.data.step =="query" || response.data.step == "unsafe"){
                            type = 'error'
                    }
                    if(response.data.step =="Invalid"){
                            type = 'warn'
                    }

                      this.$notify({
                        group: 'foo',
                            type: type,
                            title: response.data.message
                        });
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
           async copText(){
                var copyText = this.data.url;

                  try {
                    await navigator.clipboard.writeText(copyText);
                    // alert('Copied');
                    this.$notify({
                        group: 'foo',
                         type: 'success',
                        title: 'Copied'
                        });
                } catch($e) {
                     this.$notify({
                        group: 'foo',
                        type: 'error',
                        title: 'Can not copy'
                        });
                }

            },
            resetForm(){
                this.long_url = "";
                this.data = [];

            }
        }
    }
</script>
