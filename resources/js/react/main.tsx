import * as React from "react"
import * as ReactDOM from 'react-dom/client'
import {
    createBrowserRouter,
    RouterProvider,
} from "react-router-dom"
import ErrorPage from "./components/ErrorPage";
import {StudentPage} from "./Components";
import {TeacherPage} from "./Components";
import {AdminPage} from "./Components";

declare global {
    const role: string
}



const getRoutes = (chosen_role : string | null = null) : {path: string, element: any, errorElement: any}[] => {
    let result = []
    const prefix = '/portal/'
    if (chosen_role === null) {
        chosen_role = role
    }
    switch (chosen_role){
        case 'student':
            result.push({
                path: prefix + chosen_role,
                element: <StudentPage/>,
                errorElement: <ErrorPage/>
            })
            break
        case 'teacher':
            result.push({
                path: prefix + chosen_role,
                element: <TeacherPage/>,
                errorElement: <ErrorPage/>
            })
            break
        case 'admin':
            result.push({
                path: prefix + chosen_role,
                element: <AdminPage/>,
                errorElement: <ErrorPage/>
            })
            result = [...result, ...getRoutes('student'), ...getRoutes('teacher')]
            break
        case 'guest':
            result.push({
                path: prefix + chosen_role,
                element: <div>
                    <h4>Необходима регистрация</h4>
                </div>,
                errorElement: <ErrorPage/>
            })
            result = [...result, ...getRoutes('student'), ...getRoutes('teacher')]
            break
    }
    return result
}

const router = createBrowserRouter(getRoutes())

const root = ReactDOM.createRoot(document.getElementById('application'))

root.render(
    <React.StrictMode>
        <RouterProvider router={router}/>
    </React.StrictMode>
);
