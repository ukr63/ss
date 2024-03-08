import Layout from "@/views/layouts/main";
import {Metadata} from "next";
import View from '@/views/project/detail';
import Project from "@/models/project";

export const metadata: Metadata = {
    title: 'Upload'
};

const Page = async ({params}: {params: any}) => {
    const result: any = await Project.getProject(params.id);

    const {data} = result;

    return (
        <Layout>
            <View {...data} />
        </Layout>
    )
}

export default Page;
