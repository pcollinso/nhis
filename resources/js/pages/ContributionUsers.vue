<template>
  <Page>
    <page-title title="Users/Contributors"/>
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">{{ pageTitle }}</h4>
          </div>
          <div class="panel-body">
            <button
              v-if="(itemTitle === 'Individual Contributor' && canCreateIndividualContributor) || (itemTitle === 'Agency User' && canCreateAgencyUser)"
              class="btn btn-sm btn-secondary"
              @click.stop.prevent="view(null)"
            >Add {{ itemTitle }}</button>
            <div class="table-responsive">
              <table class="table table-striped m-b-0">
                <thead>
                <tr>
                  <th>FirstName</th>
                  <th>LastName</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>VerificationNo</th>
                  <th>Contribution Amt.</th>
                  <th>BloodGroup</th>
                  <th>Gender</th>
                  <th>Genotype</th>
                  <th>MaritalStatus</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!localUsers.length">
                  <td colspan="11" class="text-center">No users</td>
                </tr>
                <tr v-for="i in localUsers" :key="i.id">
                  <td>{{ i.first_name }}</td>
                  <td>{{ i.last_name }}</td>
                  <td>{{ i.phone }}</td>
                  <td>{{ i.email }}</td>
                  <td>{{ i.verification_no }}</td>
                  <td>{{ i.contribution_amount }}</td>
                  <td>{{ i.blood_group ? i.blood_group.blood_group : '' }}</td>
                  <td>{{ i.gender ? i.gender.gender : '' }}</td>
                  <td>{{ i.genotype ? i.genotype.genotype : '' }}</td>
                  <td>{{ i.marital_status ? i.marital_status.marital_status : '' }}</td>
                  <td class="with-btn" nowrap>
                    <button
                      v-if="(canUpdateIndividualContributor && itemTitle === 'Individual Contributor') || (canUpdateAgencyUser && itemTitle === 'Agency User')"
                      @click.stop.prevent="view(i)"
                      class="btn btn-sm btn-secondary m-r-2"
                    >View/Edit</button>
                    <button
                      v-if="(itemTitle === 'Individual Contributor' && canDeleteIndividualContributor) || (itemTitle === 'Agency User' && canDeleteAgencyUser)"
                      @click.stop.prevent="deleteUser(i,itemTitle)"
                      class="btn btn-sm btn-secondary m-r-2"
                    >Delete</button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div ref="modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ selectedTitle }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div v-if="errors.length" class="alert alert-warning" v-html="errors" />
              <form>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Verification No *</label>
                  <div class="col-md-6 col-sm-6">
                    <input readonly disabled type="text" class="form-control" v-model.trim="user.verification_no">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Last name *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="user.last_name">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">First name *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="user.first_name">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Middle name</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="user.middle_name">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Phone *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="user.phone">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Email *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="email" class="form-control" v-model.trim="user.email">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Contribution Amount *</label>
                  <div class="col-md-6 col-sm-6">
                    <input type="number" class="form-control" v-model.trim="user.contribution_amount">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Colour </label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="user.colour">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Height </label>
                  <div class="col-md-6 col-sm-6">
                    <input type="text" class="form-control" v-model.trim="user.height">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Gender *</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select gender"
                      label="gender"
                      :options="genders"
                      v-model="gender"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Marital status *</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select marital status"
                      label="marital_status"
                      :options="maritalStatuses"
                      v-model="maritalStatus"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Blood group</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select blood group"
                      label="blood_group"
                      :options="bloodGroups"
                      v-model="bloodGroup"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4 col-sm-4">Genotype</label>
                  <div class="col-md-6 col-sm-6">
                    <v-select
                      placeholder="Select genotype"
                      label="genotype"
                      :options="genotypes"
                      v-model="genotype"
                    />
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">

              <button
                v-if="itemTitle === 'Individual Contributor' && canCreateIndividualContributor"
                :disabled="!formOk"
                @click.stop.prevent="saveIndividualContributor()"
                type="button"
                class="btn btn-sm btn-secondary"
              >Save Individual Contributor</button>
              <button
                v-else-if="itemTitle === 'Agency User' && canCreateAgencyUser"
                :disabled="!formOk"
                @click.stop.prevent="saveAgencyUser()"
                type="button"
                class="btn btn-sm btn-secondary"
              >Save Agency User</button>

            </div>
          </div>
        </div>
      </div>
    </div>
  </Page>
</template>
<script>
  import Page from './Page';
  import PageTitle from '../components/header/PageTitle';
  import AlertMixin from '../mixins/AlertMixin';
  import PermissionMixin from '../mixins/PermissionMixin';

  const defaultUser = {
    first_name: '',
    middle_name: '',
    last_name: '',
    verification_no: '',
    phone: '',
    contribution_amount: 0,
    email: '',
    colour: '',
    height: '',
    marital_status_id: null,
    genotype_id: null,
    blood_group_id: null,
    gender_id: null,
    status: 1
  };

  export default {
    name: 'ContributionUsers',
    components: {
      Page,
      PageTitle
    },
    mixins: [AlertMixin, PermissionMixin],
    props: {
      users: {
        type: Array,
        required: true
      },
      genders: {
        type: Array,
        required: true
      },
      genotypes: {
        type: Array,
        required: true
      },
      maritalStatuses: {
        type: Array,
        required: true
      },
      pageTitle: {
        type: String,
        required: true
      },
      itemTitle: {
        type: String,
        required: true
      },
      bloodGroups: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        canCreateAgencyUser: false,
        canUpdateAgencyUser: false,
        canDeleteAgencyUser: false,
        canCreateIndividualContributor: false,
        canUpdateIndividualContributor: false,
        canDeleteIndividualContributor: false,
        localUsers: this.users,
        selectedTitle: '',
        gender: {},
        bloodGroup: {},
        maritalStatus: {},
        genotype: {},
        user: {},
        currentId: 0,
        errors: ''
      };
    },
    computed: {
      formOk() {
        const {
          bloodGroups,
          genders,
          genotypes,
          maritalStatuses,
          user: {
            first_name,
            last_name,
            verification_no,
            contribution_amount,
            gender_id,
            marital_status_id,
            blood_group_id,
            genotype_id
          }
        } = this;

        const contOk = contribution_amount ? contribution_amount > 0 : true;
        const genderOk = gender_id ? genders.some(({ id }) => id === gender_id) : true;
        const bloodGroupOk = blood_group_id ? bloodGroups.some(({ id }) => id === blood_group_id) : true;
        const genotypeOk = genotype_id ? genotypes.some(({ id }) => id === genotype_id) : true;
        const maritalStatusOk = marital_status_id ? maritalStatuses.some(({ id }) => id === marital_status_id) : true;

        return !!first_name &&
          !!last_name &&
          !!contOk &&
          !!verification_no &&
          genderOk &&
          bloodGroupOk &&
          genotypeOk &&
          maritalStatusOk;
      }
    },
    watch: {
      gender(gender) {
        this.user.gender_id = gender && gender.id ? gender.id : null;
      },
      genotype(genotype) {
        this.user.genotype_id = genotype && genotype.id ? genotype.id : null;
      },
      maritalStatus(status) {
        this.user.marital_status_id = status && status.id ? status.id : null;
      },
      bloodGroup(group) {
        this.user.blood_group_id = group && group.id ? group.id : null;
      }
    },
    mounted() {
      this.canDeleteIndividualContributor = this.hasPermission('individual-contributors:delete');
      this.canCreateIndividualContributor = this.hasPermission('individual-contributors:create');
      this.canDeleteAgencyUser = this.hasPermission('agency-users:delete');
      this.canCreateAgencyUser = this.hasPermission('agency-users:create');
      this.canUpdateIndividualContributor = this.hasPermission('individual-contributors:update');
      this.canUpdateAgencyUser = this.hasPermission('agency-users:update');
    },
    methods: {
      saveIndividualContributor() {
        const copy = { ...this.user };
        copy.first_name = copy.first_name.toUpperCase();
        copy.middle_name = copy.middle_name.toUpperCase();
        copy.last_name = copy.last_name.toUpperCase();

        if (copy.id) {
          axios
            .put(`/individual-contributors/${this.currentId}`, copy)
            .then(({ data: { success, data, message = 'Could not update individual contributor' } }) => {
              this.showToast(message, success);
              if (success) {
                $(this.$refs.modal).modal('hide');
                this.localUsers = this.localUsers.map((user) => {
                  if (data.id === user.id) return data;
                  return user;
                });
              }
            }).catch(({ response: { data: { data, message } } }) => {
            data.length <= 0 ? this.errors = message : this.errors = Object.values(data).flat().join('<br>');
          });
        } else {
          axios
            .post(`/individual-contributors`, copy)
            .then(({ data: { success, data, message = 'Could not create individual contributor' } }) => {
              this.showToast(message, success);
              if (success) {
                $(this.$refs.modal).modal('hide');
                this.localUsers.push(data);
                this.user = { ...defaultUser };
                window.location.href = process.env.MIX_BIOMETRIC_IDENTIFY_START_URL;
              }
            }).catch(({ response: { data: { data, message } } }) => {
            data.length <= 0 ? this.errors = message : this.errors = Object.values(data).flat().join('<br>');
          });
        }
      },
      saveAgencyUser() {
        const copy = { ...this.user };
        copy.first_name = copy.first_name.toUpperCase();
        copy.middle_name = copy.middle_name.toUpperCase();
        copy.last_name = copy.last_name.toUpperCase();

        if (copy.id) {
          axios
            .put(`/agency-users/${this.currentId}`, copy)
            .then(({ data: { success, data, message = 'Could not update agency user' } }) => {
              this.showToast(message, success);
              if (success) {
                $(this.$refs.modal).modal('hide');
                this.localUsers = this.localUsers.map((user) => {
                  if (data.id === user.id) return data;
                  return user;
                });
              }
            }).catch(({ response: { data: { data, message } } }) => {
            data.length <= 0 ? this.errors = message : this.errors = Object.values(data).flat().join('<br>');
          });
        } else {
          axios
            .post(`/agency-users`, copy)
            .then(({ data: { success, data, message = 'Could not create agency user' } }) => {
              this.showToast(message, success);
              if (success) {
                $(this.$refs.modal).modal('hide');
                this.localUsers.push(data);
                this.user = { ...defaultUser };
                window.location.href = process.env.MIX_BIOMETRIC_IDENTIFY_START_URL;
              }
            }).catch(({ response: { data: { data, message } } }) => {
            data.length <= 0 ? this.errors = message : this.errors = Object.values(data).flat().join('<br>');
          });
        }
      },
      view(user) {
        if (user) {
          this.user = { ...user };
          this.currentId = user.id;
          this.selectedTitle = `Update user ${this.user.first_name} ${this.user.last_name}`;
          this.gender = this.genders.find(({ id }) => id === this.user.gender_id) || {};
          this.genotype = this.genotypes.find(({ id }) => id === this.user.genotype_id) || {};
          this.bloodGroup = this.bloodGroups.find(({ id }) => id === this.user.blood_group_id) || {};
          this.maritalStatus = this.maritalStatuses.find(({ id }) => id === this.user.marital_status_id) || {};
        } else {
          this.currentId = 0;
          this.selectedTitle = 'Create contributor/user';
          this.user = { ...defaultUser };
          this.gender = {};
          this.genotype = {};
          this.bloodGroup = {};
          this.maritalStatus = {};
          axios
            .get(`/codes/user`)
            .then(({ data: { data: { verification_no } } }) => {
              this.user.verification_no = verification_no;
            });
        }
        $(this.$refs.modal).modal('show');
      },
      deleteUser(user,tit) {
        if(tit === 'Individual Contributor'){
          axios
            .delete(`/individual-contributors/${user.id}`)
            .then(({ data: { success, data } }) => {
              if (success) {
                this.showToast('Individual Contributor deleted');
                this.localUsers = this.localUsers.filter(u => u.id !== user.id);
              }
            }).catch(({ response: { data } }) => console.log("error", data));
        }else if(tit === 'Agency User'){
          axios
            .delete(`/agency-users/${user.id}`)
            .then(({ data: { success, data } }) => {
              if (success) {
                this.showToast('Agency User deleted');
                this.localUsers = this.localUsers.filter(u => u.id !== user.id);
              }
            }).catch(({ response: { data } }) => console.log("error", data));
        }
      }
    }
  }
</script>
