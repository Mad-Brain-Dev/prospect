<template>
<div class="">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-sm btn-info text-capitalize" style="padding-top: 8px;" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Import New Data
    </button>
    <!--upload Modal start-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Prospect From Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                        <div class="form">

                            <div class="mb-3">
                                <label for="formFileSm" class="form-label">Select your excel file</label>
                                <input class="form-control form-control-sm" id="formFileSm" type="file" @change="checkFile">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" @click="importExcel()">Import</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
            </div>
        </div>
    </div>
    <!--upload Modal end-->

    <!--progress Modal start-->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{message}}</h5>
                </div>
                <div class="modal-body">
                    <div class="form">

                        <div class="mb-3">
                            <label for="formFileSm" class="form-label error">Please don't refresh  or close this page.</label>
                            <div class="progress mt-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                     role="progressbar"
                                     :aria-valuenow="progressPercentage"
                                     aria-valuemin="0"
                                     aria-valuemax="100"
                                     :style="`width: ${progressPercentage}%`">{{progressPercentage}}%</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--progress Modal end-->
</div>
</template>

<script>

export default {
    name: "ExcelUploadModalComponent",
    data(){
        return{
            excel:'',
            isOpen:false,
            message:'',
            progressPercentage:0,
            batchId:null,
        }
    },
    methods:{
        checkFile(e) {
            let files = e.target.files || e.dataTransfer.files;
            // console.log('#', files); // The file is in console
            if (!files.length)
                return;
            this.createFile(files[0]);
        },
        createFile(file) {
            // console.log(this.excel)
            // console.log(file)
            this.excel = file
        },
        importExcel: function () {
            $("#exampleModal").modal("hide");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, import it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    let self = this;
                    formData.append("file", this.excel);
                    axios.post('/admin/customers/excel/file-import', formData)
                        .then((response)=>{
                            // console.log(response)
                            if (response.data.status_code == 200){
                                self.message = response.data.message
                                self.batchId = response.data.data.id
                                console.log(self.batchId)

                                self.getUploadProgress();
                                $("#progressModal").modal("show");
                                // Swal.fire({
                                //     title: "Saved",
                                //     // text: "Data imported successfully!",
                                //     text: response.data.message,
                                //     icon: "success"
                                // });
                                // window.location.reload();
                            }else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: response.data.message,
                                });
                                $("#exampleModal").modal("show");
                            }
                        })
                        .catch((error)=>{
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!",
                            });
                            $("#exampleModal").modal("show");
                        })
                }else {
                    $("#exampleModal").modal("show");
                }
            });
        },
        getUploadProgress(){
            let self = this;

            console.log('calling')
            //get progress data
            let progressResponse = setInterval(()=>{
                axios.get(`/admin/customers/excel/progress/${self.batchId}`)
                    .then((response)=>{
                        console.log(response)

                        let totalJobs = parseInt(response.data.data.total_jobs);
                        let pendingJobs = parseInt(response.data.data.pending_jobs);
                        let completedJobs = totalJobs - pendingJobs;

                        if (pendingJobs == 0){
                            self.progressPercentage = 100;
                        }else {
                            self.progressPercentage = parseInt(completedJobs / totalJobs * 100).toFixed(0);
                        }

                        if (parseInt(self.progressPercentage) >= 100){
                            clearInterval(progressResponse);
                            //close the progress model
                            $("#progressModal").modal("hide");

                            //show the success message
                            Swal.fire({
                                title: "Imported",
                                // text: "Data imported successfully!",
                                text: 'Data imported successfully',
                                icon: "success",
                                confirmButtonText: "Back",
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });


                        }
                    })
                    .catch(()=>{
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                        });
                    })
            },2000)
        }
    }
}
</script>

<style scoped>
.modal{
    pointer-events: none;
}
</style>
