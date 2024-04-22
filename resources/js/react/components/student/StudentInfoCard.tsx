import {useEffect, useState} from "react";
import axios from "axios";
import {getApiRoutes} from "../../main";
import {BulmaLevel} from "../BulmaLevel";


const StudentInfoCard = () => {

    const [user, setUser] = useState(null)


    const loadUserAxios = () => {
        return axios.get(getApiRoutes().load_user)
    }

    const getUserName = () => {
        return [user.last_name, user.name, user.second_name].filter(part => part !== null).join(' ')
    }

    useEffect(() => {
        loadUserAxios().then(r => setUser(r.data))
    }, [])


    if (user === null) return

    return <div className="box is-block theme-light">
        <h4 className="is-size-4">{getUserName()}</h4>
        <BulmaLevel label="Группа" value={user.group !== null ? user.group.code : null}/>
        <BulmaLevel label="email" value={user.email}/>
        <BulmaLevel label="Телефон" value={user.phone}/>
    </div>
}

export {StudentInfoCard}
