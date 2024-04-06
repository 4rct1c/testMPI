import * as React from "react"
import * as ReactDOM from 'react-dom/client'
import {Application} from './Components'
import {
    createBrowserRouter,
    RouterProvider,
} from "react-router-dom"
import ErrorPage from "./components/ErrorPage";
import {StudentPage} from "./components/student/StudentPage";
import {TeacherPage} from "./components/teacher/TeacherPage";

declare global {
    let role: string
}

const router = createBrowserRouter([
    {
        path: "/",
        element: <Application/>,
        errorElement: <ErrorPage/>
    },
    {
        path: "/student/",
        element: <StudentPage/>
    },
    {
        path: "/teacher/",
        element: <TeacherPage/>
    },
])
const root = ReactDOM.createRoot(document.getElementById('application'))

root.render(
    <React.StrictMode>
        <RouterProvider router={router}/>
    </React.StrictMode>
);
