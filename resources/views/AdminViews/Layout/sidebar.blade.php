


  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

       <li class="nav-item">
        <a class="nav-link " href="{{route('dashboard.view')}}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>

       <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#users" data-bs-toggle="collapse" href="#">
          <i class="bi bi-people-fill"></i><span>Users</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="users" class="nav-content collapse " data-bs-parent="#sidebar-nav">

          <li>
            <a href="{{route('users.list')}}">
              <i class="bi bi-person-lines-fill"></i><span>User List</span>
            </a>
          </li>

       <li>
            <a href="{{route('users.report')}}">
              <i class="bi bi-pie-chart-fill"></i><span>Report</span>
            </a>
          </li>


        </ul>
      </li>
      <li class="nav-item sub-menu-dropdown" id="category-parent">
        <a class="nav-link collapsed" data-bs-target="#category" data-bs-toggle="collapse" href="#">
          <i class="bi bi-tag-fill"></i><span>Categories</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="category" class="nav-content collapse" data-bs-parent="#category-parent">

          <li>
            <a href="{{url('admin/add_category')}}">
              <i class="bi bi-file-plus-fill"></i><span>Add Category</span>
            </a>
          </li>
          <li>
            <a href="{{url('admin/category_list')}}">
              <i class="bi bi-list-task"></i><span>Category List</span>
            </a>
          </li>

        </ul>
      </li>
      <li class="nav-item sub-menu-dropdown" id="form-parent">
        <a class="nav-link collapsed" data-bs-target="#form" data-bs-toggle="collapse" href="#">
          <i class="bi bi-code-square"></i><span>Forms</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="form" class="nav-content collapse" data-bs-parent="#form-parent">

          <li>
            <a href="{{url('admin/add_form')}}">
              <i class="bi bi-file-plus-fill"></i><span>Build Form</span>
            </a>
          </li>
          <li>
            <a href="{{url('admin/forms')}}">
              <i class="bi bi-list-task"></i><span>Form List</span>
            </a>
          </li>

        </ul>
      </li>
      <li class="nav-item " role="button">
        <a class="nav-link text-muted  bg-white" href="{{route('transactions.get')}}">
          <i class="bi bi-cash-stack text-muted" ></i>
          <span>Transactions</span>
        </a>
      </li>
      <li class="nav-item" role="button">
        <a class="nav-link text-muted  bg-white" href="{{route('form.all')}}">
          <i class="bi bi-file-earmark-post text-muted" ></i>
          <span>All Posts</span>
        </a>
      </li>

      <li class="nav-item" role="button">
        <a class="nav-link text-muted  bg-white" href="{{route('contact.messages')}}">
          <i class="bi bi-envelope-fill text-muted" ></i>
          <span>Messages</span>
        </a>
      </li>
    </ul>

  </aside><!-- End Sidebar-->
