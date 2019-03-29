const getters = {
  init_layout: state => state.app.init_layout,
  user: state => state.app.user,
  project: state => state.app.project,
  project_list: state => state.app.project_list,
  project_id: state => state.app.project_id,
  category_list: state => state.app.category_list,
  api_list: state => state.app.api_list,

  show_category: state => state.form.show_category,
  category_form: state => state.form.category_form,
  show_api_form: state => state.form.show_api_form,
  api_form: state => state.form.api_form,
  show_reg: state => state.form.show_reg,
  show_login: state => state.form.show_login,
  show_project: state => state.form.show_project,
  api_view_list: state => state.api.list,
  api_view_id: state => state.api.id
}
export default getters
